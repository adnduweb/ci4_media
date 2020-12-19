<?php

namespace Adnduweb\Ci4Media\Controllers\Admin;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Adnduweb\Ci4Admin\Libraries\Theme;
//use Tatter\Exports\Exceptions\ExportsException;
use  Adnduweb\Ci4Media\Config\Files as MediasConfig;
use  Adnduweb\Ci4Media\Exceptions\MediasException;
use  Adnduweb\Ci4Media\Entities\Media;
use  Adnduweb\Ci4Media\Models\MediaModel;
use CodeIgniter\API\ResponseTrait;

class Medias extends \Adnduweb\Ci4Admin\Controllers\BaseAdminController
{
	use ResponseTrait;
	/**
	 * name controller
	 */
	public $controller = 'medias';

	/**
	 * Localize slug
	 */
	public $pathcontroller  = '';

	/**
	 * Files config.
	 *
	 * @var MediasConfig
	 */
	protected $config;

	/**
	 * Helpers to load.
	 */
	protected $helpers = ['auth', 'detect', 'alerts', 'medias', 'handlers', 'html', 'text', 'form'];

	// /**
	//  * Overriding data for views.
	//  *
	//  * @var array
	//  */
	// protected $viewData = [];

	/**
	 * name model
	 */
	public $tableModel = MediaModel::class;

	/**
	 * Preloads the configuration and verifies the storage directory.
	 * Parameters are mostly for testing purposes.
	 *
	 * @param MediasConfig|null $config
	 * @param MediaModel|null $model
	 *
	 * @throws MediasException
	 */
	public function __construct(MediasConfig $config = null, MediaModel $model = null)
	{
		$this->config = $config ?? config('Medias');

		// Use the short model name so a child may be loaded first
		$this->model = $model ?? model('MediaModel');

		// Verify the storage directory
		MediaModel::storage();
	}

	// /**
	//  * Verify authentication is configured correctly *after* parent calls loadHelpers().
	//  *
	//  * @param RequestInterface         $request
	//  * @param ResponseInterface        $response
	//  * @param \Psr\Log\LoggerInterface $logger
	//  *
	//  * @throws \CodeIgniter\HTTP\Exceptions\HTTPException
	//  * @see https://codeigniter4.github.io/CodeIgniter4/extending/authentication.html
	//  */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);

		if (!function_exists('user_id') || !empty($this->config->failNoAuth)) {
			throw new MediasException(lang('Medias.noAuth'));
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Charge Params JS
	 */

	protected function initParamJs()
	{
		parent::initParamJs();

		$uploadLimitBytes = max_file_upload_in_bytes();

		// Buffer chunks to be just under the limit (maintain bytes)
		$chunkSize = $uploadLimitBytes - 1000;

		// Limit files to the MB equivalent of 500 chunks
		$maxFileSize = round($chunkSize * 500 / 1024 / 1024, 1);


		$this->viewData['paramJs']['Medias'] =
			json_encode([
				'uploadLimitBytes' => max_file_upload_in_bytes(),
				'chunkSize' => $chunkSize,
				'maxFileSize' =>   $maxFileSize,
				'acceptedFiles' => 'image/*,application/pdf',
				'maxFiles' => 20
			]);
	}

	/**
	 * Handles the final display of files based on $data.
	 *
	 * @return string
	 */
	public function display(): string
	{
		// Apply any defaults for missing metadata
		$this->setDefaults();

		Theme::add_js('/resources/metronic/js/pages/custom/medias/imageManager.js');

		$this->getToolbar();

		// Get the Files
		if (!isset($this->viewData['files'])) {
			// Apply a target user
			if ($this->viewData['userId']) {
				$this->model->whereUser($this->viewData['userId']);
			}

			// Apply any requested search filters
			if ($this->viewData['search']) {
				$this->model->like('filename', $this->viewData['search']);
			}

			// Sort and order
			$this->model->orderBy($this->viewData['sort'], $this->viewData['order']);

			// Paginate non-select formats
			if ($this->viewData['format'] !== 'select') {
				$this->setData([
					'files' => $this->model->paginate($this->viewData['perPage'], 'default', $this->viewData['page']),
					'pager' => $this->model->pager,
				], true);
			} else {
				$this->setData([
					'files' => $this->model->findAll()
				], true);
			}
		}

		// AJAX calls skip the wrapping
		if ($this->viewData['ajax']) {
			// return view('Adnduweb\Ci4Media\Views\Formats\\' . $this->viewData['format'], $this->viewData);
			return $this->_render('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\Formats\\' . $this->viewData['format'], $this->viewData);
		}

		return $this->_render('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\index', $this->viewData);
	}

	//--------------------------------------------------------------------

	/**
	 * Lists of files; if global listing is not permitted then
	 * falls back to user().
	 *
	 * @return RedirectResponse|string
	 */
	public function index()
	{
		// // Check for list permission
		// if (! $this->model->mayList())
		// {
		// 	return $this->user();
		// }

		return $this->display();
	}

	/**
	 * Filters files for a user (defaults to the current user).
	 *
	 * @param string|integer|null $userId ID of the target user
	 *
	 * @return ResponseInterface|ResponseInterface|string
	 */
	public function user($userId = null)
	{
		// Figure out user & access
		$userId = $userId ?? user_id() ?? 0;

		// Not logged in
		if (!$userId) {
			// Check for list permission
			if (!$this->model->mayList()) {
				return $this->failure(403, lang('Permits.notPermitted'));
			}

			$this->setData([
				'access'   => 'display',
				'title'    => 'All Files',
				'username' => '',
			]);
		}
		// Logged in, looking at another user
		elseif ($userId != user_id()) {
			// Check for list permission
			if (!$this->model->mayList()) {
				return $this->failure(403, lang('Permits.notPermitted'));
			}

			$this->setData([
				'access'   => $this->model->mayAdmin() ? 'manage' : 'display',
				'title'    => 'User Files',
				'username' => 'User',
			]);
		}
		// Looking at own files
		else {
			$this->setData([
				'access'   => 'manage',
				'title'    => 'My Files',
				'username' => 'My',
			]);
		}

		$this->setData([
			'userId' => $userId,
			'source' => 'user/' . $userId,
		]);

		return $this->display();
	}

	//--------------------------------------------------------------------

	/**
	 * Display the Dropzone uploader.
	 *
	 * @return ResponseInterface|string
	 */
	public function new()
	{
		// Check for create permission
		if (!$this->model->mayCreate()) {
			return $this->failure(403, lang('Permits.notPermitted'));
		}

		return view('Adnduweb\Ci4Admin\themes\/'. $this->settings->setting_theme_admin.'/\new');
	}

	/**
	 * Displays or processes the form to rename a file.
	 *
	 * @param string|null $fileId
	 *
	 * @return ResponseInterface|string
	 */
	public function rename($fileId = null)
	{
		// Load the request
		$fileId = $this->request->getGetPost('file_id') ?? $fileId;
		$file   = $this->model->find($fileId);

		// Handle missing info
		if (empty($file)) {
			return $this->failure(400, lang('Medias.noFile'));
		}

		// Check for form submission
		if ($filename = $this->request->getGetPost('filename')) {
			// Update the name
			$file->filename = $filename;
			$this->model->save($file);

			// AJAX requests are blank on success
			return $this->request->isAJAX()
				? ''
				: redirect()->back()->with('message', lang('Medias.renameSuccess', [$filename]));
		}

		// AJAX skips the wrapper
		return view(
			$this->request->isAJAX() ? 'Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\Forms\rename' : 'Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\rename',
			[
				'config' => $this->config,
				'file'   => $file,
			]
		);
	}

	// /**
	//  * Deletes a file.
	//  *
	//  * @param string $fileId
	//  *
	//  * @return ResponseInterface
	//  */
	public function removeFile(...$params)
	{

		// Load post data
		$post = $this->request->getPost();

		if (!empty($post)) {
			$uuid = $post['uuid'];
		}

		if (!empty($params)) {
			$uuid = $params[0];
		}



		$this->uuid = $this->uuid->fromString($uuid)->getBytes();
		$media = $this->tableModel->where([$this->tableModel->uuidFields[0] => $this->uuid])->first();


		if (empty($media)) {
			// $response = ['error' => ['code' => 400, 'message' => lang('Medias.noFile')], 'success' => false, csrf_token() => csrf_hash()];
			// return $this->respond($response, 400);
			return;
		}

		if ($this->model->delete($media->id)) {
			@unlink(config('Medias')->storagePath . 'thumbnails/' . $media->localname);
			@unlink(config('Medias')->storagePath . 'small/' . $media->localname);
			@unlink(config('Medias')->storagePath . 'medium/' . $media->localname);
			@unlink(config('Medias')->storagePath . 'large/' . $media->localname);
			@unlink(config('Medias')->storagePath . 'original/' . $media->localname);

			//lob(config('Medias')->storagePath . 'custom/' . stristr($this->viewData['media']->localname, '.', true) . '*');
			$custom = [];
			$customFiles = glob(config('Medias')->storagePath . 'custom/' . stristr($media->localname, '.', true) . '*');

			if (!empty($customFiles)) {
				@unlink(config('Medias')->storagePath . 'custom/' . $media->localname);
			} 

			if ($this->request->isAJAX()) {
				$html = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\Forms\files', $this->dataImageManager());
				$response = ['success' => ['code' => 200, 'message' => lang('Medias.deleteSuccess')], 'error' => false, 'html' => $html, csrf_token() => csrf_hash()];
				return $this->respond($response,  200);
			}

			Theme::set_message('success', lang('Medias.deleteSuccess'), lang('Core.cool_success'));
			return redirect()->back();
		}
		if ($this->request->isAJAX()) {
			$response = ['error' => ['code' => 500, 'message' => $this->model->errors()], 'success' => false, csrf_token() => csrf_hash()];
			return $this->respond($response, 400);
		}
	}

	// /**
	//  * Deletes a file.
	//  *
	//  * @param string $fileId
	//  *
	//  * @return ResponseInterface
	//  */
	public function removeAll()
	{
		helper('file');

		// delete Files
		$map = directory_map(config('Medias')->storagePath, FALSE, TRUE);

		foreach ($map as $k => $v) {

			if (is_array($v)) {
				foreach ($v as $file) {
					delete_files(Config('Medias')->storagePath . $k);
				}
			}
		}

		// delete Files TEMP
		$mapTemp = directory_map(WRITEPATH . 'uploads/', FALSE, TRUE);


		foreach ($mapTemp as $k => $v) {

			if (is_array($v)) {
				foreach ($v as $file) {
					delete_files(WRITEPATH . 'uploads/' . $k);
				}
			}

			//rrmdir(WRITEPATH . 'uploads');
			delete_directory(WRITEPATH . 'uploads');
		}

		// Recreate the uploads folder
		if (!is_dir(WRITEPATH . 'uploads/') && !@mkdir(WRITEPATH . 'uploads/', 0775, true)) {
			throw MediasException::forDirFail(WRITEPATH . 'uploads/');
		}

		if ($this->model->deleteAll()) {

			Theme::set_message('success', lang('Medias.deleteSuccess'), lang('Core.cool_success'));
			return $this->_redirect('');
		}


		Theme::set_message('danger', implode('. ', $this->model->errors()), lang('Core.warning_error'));
		return $this->_redirect('');
	}

	/**
	 * Handles bulk actions.
	 *
	 * @return ResponseInterface
	 */
	public function bulk(): ResponseInterface
	{
		// Load post data
		$post = $this->request->getPost();

		// Harvest file IDs and the requested action
		$action  = '';
		$fileIds = [];
		foreach ($post as $key => $value) {
			if (is_numeric($value)) {
				$fileIds[] = $value;
			} else {
				$action = $key;
			}
		}

		// Make sure some files where checked
		if (empty($fileIds)) {
			return $this->failure(400, lang('Medias.noFile'));
		}

		// Handle actions
		if (empty($action)) {
			return $this->failure(400, 'No valid action');
		}

		// Bulk delete request
		if ($action === 'delete') {
			$this->model->delete($fileIds);
			return redirect()->back()->with('success', 'Deleted ' . count($fileIds) . ' files.');
		}

		// Bulk export of some kind, match the handler
		if (!$handler = handlers('Exports')->where(['slug' => $action])->first()) {
			return $this->failure(400, 'No handler found for ' . $action);
		}

		$export = new $handler();
		foreach ($fileIds as $fileId) {
			if ($file = $this->model->find($fileId)) {
				$export->setFile($file->object->setBasename($file->filename));
			}
		}

		try {
			$result = $export->process();
		} catch (ExportsException $e) {
			return $this->failure(400, $e->getMessage());
		}

		alert('success', 'Processed ' . count($fileIds) . ' files.');
		return $result;
	}

	/**
	 * Receives uploads from Dropzone.
	 *
	 * @return ResponseInterface|string
	 */
	public function upload()
	{
		helper('string');
		// // Check for create permission
		// if (! $this->model->mayCreate())
		// {
		// 	return $this->failure(403, lang('Permits.notPermitted'));
		// }

		// Verify upload succeeded
		$upload = $this->request->getFile('file');

		$this->rules = [
			'file' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/gif,image/png,text/plain,text/csv,application/vnd.ms-excel,application/pdf,application/msword,application/msword]'
        ];

		// In the controller
		if (!$this->validate($this->rules)) {
			$response = ['error' => ['code' => 400, 'message' => lang('Medias.noFileAuthorized')], 'success' => false, csrf_token() => csrf_hash()];
			return $this->respond($response, 400);
        }

		if (empty($upload)) {
			return $this->failure(400, lang('Medias.noFile'));
		}
		if (!$upload->isValid()) {
			return $upload->getErrorString() . '(' . $upload->getError() . ')';
		}

		// Check for chunks
		if ($this->request->getPost('dzChunkIndex') !== null) {
			// Gather chunk info
			$chunkIndex  = $this->request->getPost('dzChunkIndex');
			$totalChunks = $this->request->getPost('dzTotalChunkCount');
			$uuid        = $this->request->getPost('dzUuid');

			// Check for chunk directory
			$chunkDir = config('Medias')->storagePath . 'original';
			if (!is_dir($chunkDir) && !mkdir($chunkDir, 0775, true)) {
				throw MediasException::forChunkDirFail($chunkDir);
			}

			// Check for chunk directory
			$chunkDir = WRITEPATH . 'uploads/' . $uuid;
			if (!is_dir($chunkDir) && !mkdir($chunkDir, 0775, true)) {
				throw MediasException::forChunkDirFail($chunkDir);
			}

			// Move the file
			$upload->move($chunkDir, $chunkIndex . '.' . $upload->getExtension());

			// Check for more chunks
			if ($chunkIndex < $totalChunks - 1) {
				session_write_close();
				return '';
			}

			// Merge the chunks
			$path = $this->mergeChunks($chunkDir);
		}

		// Get additional post data to pass to model
		$data = $this->request->getPost();
		$data['filename']   = $data['filename'] ?? $upload->getClientName();
		$data['clientname'] = $data['clientname'] ?? $upload->getClientName();
		$data['uuid'] = $uuid;

		// Accept the file
		$file = $this->model->createFromPath($path ?? $upload->getRealPath(), $data);

		if ($this->request->isAJAX()) {

			$html = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\Forms\files', $this->dataImageManager());
			$response = ['success' => ['code' => 200, 'message' => lang('Medias.deleteSuccess')], 'error' => false, 'html' => $html, csrf_token() => csrf_hash()];
			return $this->respond($response,  200);

		}

		return redirect()->back()->with('message', lang('File.uploadSucces', [$file->clientname]));
	}

	/**
	 * Merges all chunks in a target directory into a single file, returns the file path.
	 *
	 * @return string
	 *
	 * @throws MediasException
	 */
	protected function mergeChunks($dir): string
	{
		helper('filesystem');
		helper('text');

		// Get chunks from target directory
		$chunks = get_filenames($dir, true);
		if (empty($chunks)) {
			throw MediasException::forNoChunks($dir);
		}

		// Create the temp file
		$tmpfile = tempnam(sys_get_temp_dir(), random_string());
		log_message('debug', 'Merging ' . count($chunks) . ' chunks to ' . $tmpfile);

		// Open temp file for writing
		$output = @fopen($tmpfile, 'ab');
		if (!$output) {
			throw MediasException::forNewFileFail($tmpfile);
		}

		// Write each chunk to the temp file
		foreach ($chunks as $file) {
			$input = @fopen($file, 'rb');
			if (!$input) {
				throw MediasException::forWriteFileFail($tmpfile);
			}

			// Buffered merge of chunk
			while ($buffer = fread($input, 4096)) {
				fwrite($output, $buffer);
			}

			fclose($input);
		}

		// close output handle
		fclose($output);

		return $tmpfile;
	}

	/**
	 * Processes Export requests.
	 *
	 * @param string         $slug   The slug to match to Exports attribute
	 * @param string|integer $fileId
	 *
	 * @return ResponseInterface
	 */
	public function export(string $slug, $fileId): ResponseInterface
	{
		// Match the export handler
		$handler = handlers('Exports')->where(['slug' => $slug])->first();
		if (empty($handler)) {
			alert('warning', 'No handler found for ' . $slug);
			return redirect()->back();
		}

		// Load the file
		$file = $this->model->find($fileId);
		if (empty($file)) {
			alert('warning', lang('Medias.noFile'));
			return redirect()->back();
		}

		// Pass to the handler
		$export   = new $handler($file->object);
		$response = $export->setFilename($file->filename)->process();

		// If the handler returned a response then we're done
		if ($response instanceof ResponseInterface) {
			return $response;
		}

		return redirect()->back();
	}

	/**
	 * Outputs a file thumbnail directly as image data.
	 *
	 * @param string|integer $fileId
	 *
	 * @return ResponseInterface
	 */
	public function thumbnail($fileId): ResponseInterface
	{
		if ($file = $this->model->find($fileId)) {
			$path = $file->getThumbnail();
		} else {
			$path = File::locateDefaultThumbnail();
		}

		return $this->response->setHeader('Content-type', 'image/jpeg')->setBody(file_get_contents($path));
	}

	//--------------------------------------------------------------------

	/**
	 * Handles failures.
	 *
	 * @param int $code
	 * @param string $message
	 * @param boolean|null $isAjax
	 *
	 * @return ResponseInterface|RedirectResponse
	 */
	protected function failure(int $code, string $message, bool $isAjax = null): ResponseInterface
	{
		log_message('debug', $message);

		if ($isAjax ?? $this->request->isAJAX()) {
			return $this->response
				->setStatusCode($code)
				->setJSON(['error' => $message]);
		}

		return redirect()->back()->with('error', $message);
	}

	/**
	 * Sets a value in $this->viewData, overwrites optional.
	 *
	 * @param array<string, mixed> $data
	 * @param boolean $overwrite
	 *
	 * @return $this
	 */
	protected function setData(array $data, bool $overwrite = false): self
	{
		if ($overwrite) {
			$this->viewData = array_merge($this->viewData, $data);
		} else {
			$this->viewData = array_merge($data, $this->viewData);
		}

		return $this;
	}

	/**
	 * Merges in the default metadata.
	 *
	 * @return $this
	 */
	protected function setDefaults(): self
	{
		return $this->setData([
			'source'   => '',
			'layout'   => 'public',
			'files'    => null,
			'selected' => explode(',', $this->request->getVar('selected') ?? ''),
			'userId'   => null,
			'username' => '',
			'ajax'     => $this->request->isAJAX(),
			'search'   => $this->request->getVar('search'),
			'sort'     => $this->getSort(),
			'order'    => $this->getOrder(),
			'format'   => $this->getFormat(),
			'perPage'  => $this->getPerPage(),
			'page'     => $this->request->getVar('page'),
			'pager'    => null,
			//'access'   => $this->model->mayAdmin() ? 'manage' : 'display',
			'access'   => 'manage',
			'exports'  => $this->getExports(),
			'bulks'    => handlers()->where(['bulk' => 1])->findAll(),
		]);
	}

	/**
	 * Determines the sort field.
	 *
	 * @return string
	 */
	protected function getSort(): string
	{
		// Check for a request, then load from Settings
		$sorts = [
			$this->request->getVar('sort'),
			service('settings')->filesSort,
		];

		foreach ($sorts as $sort) {
			// Validate
			if (in_array($sort, $this->model->allowedFields)) // @phpstan-ignore-line
			{
				// Update user setting with the new preference
				service('settings')->filesSort = $sort;
				return $sort;
			}
		}

		return 'filename';
	}

	/**
	 * Determines the sort order.
	 *
	 * @return string
	 */
	protected function getOrder(): string
	{
		// Check for a request, then load from Settings
		$orders = [
			$this->request->getVar('order'),
			service('settings')->filesOrder,
		];

		foreach ($orders as $order) {
			$order = strtolower($order);

			// Validate
			if (in_array($order, ['asc', 'desc'])) {
				// Update user setting with the new preference
				service('settings')->filesOrder = $order;
				return $order;
			}
		}

		return 'asc';
	}

	/**
	 * Determines items per page.
	 *
	 * @return int
	 */
	protected function getPerPage(): int
	{
		// Check for a request, then load from Settings
		$nums = [
			$this->request->getVar('perPage'),
			service('settings')->perPage,
		];

		foreach ($nums as $num) {
			// Validate
			if (is_numeric($num) && (int) $num > 0) {
				// Update user setting with the new preference
				service('settings')->perPage = $num;
				return $num;
			}
		}

		return 8;
	}

	/**
	 * Determines the display format.
	 *
	 * @return string
	 */
	protected function getFormat(): string
	{
		// Check for a request, then load from Settings, fallback to the config default
		$formats = [
			$this->request->getVar('format'),
			service('settings')->filesFormat,
			$this->config->defaultFormat,
		];

		foreach ($formats as $format) {
			// Validate
			if (in_array($format, ['cards', 'list', 'select'])) {
				// Update user setting with the new preference
				service('settings')->filesFormat = $format;
				return $format;
			}
		}

		return 'cards';
	}

	/**
	 * Gets Export handlers indexed by the extension they support.
	 *
	 * @return array<string, array>
	 */
	protected function getExports(): array
	{
		$exports = [];
		foreach (handlers('Exports')->findAll() as $class) {
			$attributes = handlers()->getAttributes($class);

			// Add the class name for easy access later
			$attributes['class'] = $class;

			foreach (explode(',', $attributes['extensions']) as $extension) {
				$exports[$extension][] = $attributes;
			}
		}

		return $exports;
	}

	public function getManagerEdition()
	{
		if ($value = $this->request->getPost('value')) {
			$this->uuid = $this->uuid->fromString($value['uuid'])->getBytes();
			$this->viewData['media'] = $this->tableModel->where([$this->tableModel->uuidFields[0] => $this->uuid])->first();
			$this->infosCustomImage($this->viewData['media']);

			$html = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\imageManagerEdition', $this->viewData);
			return $this->respond(['status' => true, 'type' => 'success', 'html' => $html], 200);
		}
	}

	public function saveManagerEdition()
	{
		if ($value = $this->request->getPost('value')) {
			parse_str($value, $output);

			//print_r($output); exit;

			$image = new Media($this->tableModel->where($this->tableModel->primaryKey, $output['id_media'])->get()->getRowArray());

			if (!empty($output['lang'][1]['titre'])) {

				foreach ($output['lang'] as $lang) {

					if (!$image->savelang($output['lang'], $output['id_media'])) {
						throw DataException::forProblemSaving($this->tableModel->errors(true));
					}
				}
			}

			$response = ['success' => ['code' => 200, 'message' => lang('Core.success_update')], 'error' => false, csrf_token() => csrf_hash()];
			// print_r($response); exit;
			return $this->respond($response,  200);
		}
	}

	public function dataImageManager()
	{

		$this->setDefaults();

		// Paginate non-select formats
		if ($this->viewData['format'] !== 'select') {
			$this->setData([
				'files' => $this->model->paginate($this->viewData['perPage'], 'default', $this->viewData['page']),
				'pager' => $this->model->pager,
			], true);
		} else {
			$this->setData([
				'files' => $this->model->findAll()
			], true);
		}

		$array = [
			'source' => $this->viewData['source'],
			'format' => $this->viewData['format'],
			'files' => $this->viewData['files'],
			'access' => 'display',
			'exports' => $this->getExports(),
			'pager' => $this->viewData['pager'],
			'perPage' => $this->viewData['perPage']
		];

		return $array;
	}



	public function getCropTemplate()
	{

		// Load post data
		$post = $this->request->getPost();

		$this->uuid = $this->uuid->fromString($post['uuid'])->getBytes();

		$this->viewData['media'] = $this->tableModel->where([$this->tableModel->uuidFields[0] => $this->uuid])->first();

		if (!$this->viewData['media']) {
			$response = ['error' => ['code' => 400, 'message' => lang('Medias.noFile')], 'success' => false, csrf_token() => csrf_hash()];
			return $this->respond($response, 400);
		}

		$this->viewData['dir_image_original'] =  config('Medias')->storagePath . 'original/';
		$this->viewData['uuid'] = $post['uuid'];
		$this->viewData['field'] = (isset($value['field'])) ? $value['field'] : false;
		$this->viewData['mine'] = $this->viewData['media']->getType();
		$this->viewData['extension'] = $this->viewData['media']->getExtension();
		$this->viewData['image'] = $this->viewData['media']->localname;

		$this->viewData['crop_width'] = (isset($value['crop_width'])) ? $value['crop_width'] : false;
		$this->viewData['crop_height'] = (isset($value['crop_height'])) ? $value['crop_height'] : false;
		$this->viewData['only'] = (isset($value['only'])) ? $value['only'] : false;
		$this->viewData['input'] = '';

		$html = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\cropImage', $this->viewData);
		//return $this->respond(['status' => true, 'type' => 'success', 'path' => site_url('public/uploads/' . $newName)], 200);
		$return = [
			'status' => true,
			'type' => 'success',
			'crop' => true,
			'cropImage' => $html,
			'path' => img_data($this->viewData['media']->getThumbnail())
		];
		return $this->response->setJSON($return);
	}

	public function cropFile()
	{

		// Load post data
		$post = $this->request->getPost();

		$this->uuid = $this->uuid->fromString($post['uuid'])->getBytes();

		$this->viewData['media'] = $this->tableModel->where([$this->tableModel->uuidFields[0] => $this->uuid])->first();


		if (!$this->viewData['media']) {
			$response = ['error' => ['code' => 400, 'message' => lang('Medias.noFile')], 'success' => false, csrf_token() => csrf_hash()];
			return $this->respond($response, 400);
		}


		$file = $this->request->getFile('croppedImage');

		if ($file->isValid() && !$file->hasMoved()) {
			// On enregistre l'image que l 'on vient de créer
			list($width, $height, $type, $attr) =  getimagesize($file->getPathName());
			$name = str_replace('.' . $this->viewData['media']->getExtension(), '-' . $width . 'x' . $height . '.' .  $this->viewData['media']->getExtension(), $this->viewData['media']->nameFile());
			if (!$file->move(config('Medias')->storagePath . 'custom/', $name)) {
				return $this->respond(['status' => false, 'type' => 'warning', 'message' => $file->getErrorString() . '(' . $file->getError() . ')']);
			}
			$mediaCustomEdition = [];
			if ($this->request->getPost('imageCustomEdition') == true) {
				$this->infosCustomImage($this->viewData['media']);
				$mediaCustomEdition = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\imageCustom', $this->viewData);
			}

			$response =
				[
					'success' =>
					[
						'code' => 200,
						'message' =>
						lang('Core.success_update')
					],
					'error' => false,
					csrf_token() => csrf_hash(),
					'field'              => $this->request->getPost('field'),
					'only'               => $this->request->getPost('only'),
					'input'              => $this->request->getPost('input'),
					'imageCustomEdition' => $mediaCustomEdition,
					'idMedia'            => $this->viewData['media']->getIdMedia(),
					'name'               => $name,
					'filename'           => img_data(config('Medias')->storagePath . 'custom/' . $name),
					'format'             => $width . 'x' . $height,
					'widgetOption'       => json_encode(['media' => [$this->tableModel->primaryKey => $this->viewData['media']->getIdMedia(), 'filename' => img_data(config('Medias')->storagePath . 'custom/' . $name), 'format' => $name]]),
					'pathThumbnail'      => img_data($this->viewData['media']->getThumbnail()),
					'path'               => img_data($this->viewData['media']->getOriginal())
				];


			return $this->respond($response,  200);
		}
	}

	protected function infosCustomImage($image)
	{
		if (!empty($image)) {
			$customFiles = glob(config('Medias')->storagePath . 'custom/' . stristr($image->localname, '.', true) . '*');
			$this->viewData['media']->custom = [];
			if (!empty($customFiles)) {

				$i = 0;
				foreach ($customFiles as $file) {
					$newFile = str_replace(config('Medias')->storagePath . 'custom/', '', $file);
					$pathinfo  = pathinfo($newFile);
					if (!empty($pathinfo)) {
						$custom[$pathinfo['filename']] = $newFile;
						$i++;
					}
				}
				$this->viewData['media']->custom = $custom;
			}
			
		}
		return $this->viewData['media'];
	}

	public function deteteFileCustom()
	{
			// Load post data
			$post = $this->request->getPost();

			$this->uuid = $this->uuid->fromString($post['uuid'])->getBytes();
			$this->format = $post['format'];

			$this->viewData['media'] = $this->tableModel->where([$this->tableModel->uuidFields[0] => $this->uuid])->first();

			$file = config('Medias')->storagePath . 'custom/' . $this->format;
			if (!@unlink($file)) {
				$response = ['error' => ['code' => 400, 'message' => lang('Medias.noFile')], 'success' => false, csrf_token() => csrf_hash()];
				return $this->respond($response, 400);
			}

			$this->infosCustomImage($this->viewData['media']);
			$mediaCustomEdition = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\imageCustom', $this->viewData);

			$response = ['success' => ['code' => 200, 'message' => lang('Medias.delete_file_success')], 'error' => false, 'customImage' => $mediaCustomEdition, csrf_token() => csrf_hash()];
			return $this->respond($response,  200);
	}

	public function getDisplayImageManager()
	{
		$html = view('Adnduweb\Ci4Media\themes\/'. $this->settings->setting_theme_admin.'/\Forms\files', $this->dataImageManager());
		$response = ['success' => ['code' => 200, 'message' => lang('Medias.deleteSuccess')], 'error' => false, 'html' => $html, csrf_token() => csrf_hash()];
		return $this->respond($response,  200);
	}
}
