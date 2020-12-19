<?php namespace Adnduweb\Ci4Media\Models;

use CodeIgniter\Files\File as CIFile;
use Michalsn\Uuid\UuidModel;
use Adnduweb\Ci4Media\Entities\Media;
use Adnduweb\Ci4Media\Exceptions\MediasException;
use Adnduweb\Ci4Core\Exceptions\ThumbnailsException;


class MediaModel extends UuidModel
{
	use \Tatter\Relations\Traits\ModelTrait, \Adnduweb\Ci4Core\Traits\AuditsTrait, \Adnduweb\Ci4Core\Models\BaseModel;
	//use \Tatter\Permits\Traits\PermitsTrait;

	protected $table        = 'medias';
	protected $primaryKey   = 'id';
	protected $returnType   = Media::class;
	protected $tableLang    = 'medias_langs';
	protected $with         = ['medias_langs'];
	protected $uuidFields   = ['uuid'];

	protected $useTimestamps  = true;
	protected $useSoftDeletes = false;
	protected $skipValidation = false; 

	protected $searchTable = ['medias', 'medias_langs'];

	protected $allowedFields = [
		'uuid',
		'filename',
		'localname',
		'clientname',
		'type',
		'size',
		'ext',
		'thumbnail',
	];

	protected $validationRules = [
		'filename' => 'required|max_length[255]',
		// file size in bytes
		'size'     => 'permit_empty|is_natural',
	];

	// Audits
	protected $afterInsert = ['auditInsert'];
	protected $afterUpdate = ['auditUpdate'];
	protected $afterDelete = ['auditDelete'];

	// Permits
	protected $mode       = 04660;
	protected $userKey    = 'user_id';
	protected $pivotKey   = 'media_id';
	protected $usersPivot = 'medias_users';

	//--------------------------------------------------------------------

	public function __construct()
    {
        parent::__construct();
		$this->builder           = $this->db->table('medias');
		$this->builder_langs     = $this->db->table('medias_langs');
		$this->builder_downloads = $this->db->table('medias_downloads');
		$this->builder_users     = $this->db->table('medias_users');

    }

	/**
	 * Normalizes and creates (if necessary) the storage and thumbnail paths.
	 *
	 * @return string The normalized storage path
	 *
	 * @throws MediasException
	 */
	public static function storage(): string
	{
		// Normalize the path
		$storage = realpath(config('Medias')->storagePath) ?: config('Medias')->storagePath;
		$storage = rtrim($storage, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		if (! is_dir($storage) && ! @mkdir($storage, 0775, true))
		{
			throw MediasException::forDirFail($storage);
		}

		// Normalize the path
		$thumbnails = $storage . 'thumbnails';
		if (! is_dir($thumbnails) && ! @mkdir($thumbnails, 0775, true))
		{
			throw MediasException::forDirFail($thumbnails);
		}

		// Normalize the path
		$small = $storage . 'small';
		if (! is_dir($small) && ! @mkdir($small, 0775, true))
		{
			throw MediasException::forDirFail($small);
		}

		// Normalize the path
		$original = $storage . 'original';
		if (! is_dir($original) && ! @mkdir($original, 0775, true))
		{
			throw MediasException::forDirFail($original);
		}

		// Normalize the path
		$medium = $storage . 'medium';
		if (! is_dir($medium) && ! @mkdir($medium, 0775, true))
		{
			throw MediasException::forDirFail($medium);
		}

		// Normalize the path
		$large = $storage . 'large';
		if (! is_dir($large) && ! @mkdir($large, 0775, true))
		{
			throw MediasException::forDirFail($large);
		}

		// Normalize the path
		$custom = $storage . 'custom';
		if (! is_dir($custom) && ! @mkdir($custom, 0775, true))
		{
			throw MediasException::forDirFail($custom);
		}

		return $storage;
	}

	//--------------------------------------------------------------------

	/**
	 * Associates a file with a user
	 *
	 * @param integer $mediaId
	 * @param integer $userId
	 *
	 * @return boolean
	 */
	public function addToUser(int $mediaId, int $userId): bool
	{
		return (bool) $this->db->table('medias_users')->insert([
			'media_id' => $mediaId,
			'user_id' => $userId,
		]);
	}

	/**
	 * Associates a file with an lang
	 *
	 * @param integer $mediaId
	 * @param integer $userId
	 *
	 * @return boolean
	 */
	public function addToLang(int $mediaId, int $lang): bool
	{
		return (bool) $this->db->table('medias_langs')->insert([
			'media_id' => $mediaId,
			'id_lang' => $lang,
		]);
	}


	/**
	 * Returns an array of all a user's Files
	 *
	 * @param integer $userId
	 *
	 * @return array
	 */
	public function getForUser(int $userId): array
	{
		return $this->whereUser($userId)->findAll();
	}

	/**
	 * Adds a where filter for a specific user.
	 *
	 * @param integer $userId
	 *
	 * @return $this
	 */
	public function whereUser(int $userId): self
	{
		$this->select('files.*')
			->join('medias_users', 'medias_users.media_id = files.id', 'left')
			->where('user_id', $userId);

		return $this;
	}

	/**
	 * 
	 */
	public function deleteAll(){
		$this->db->table('medias')->emptyTable('medias');
		$this->db->table('medias')->emptyTable('medias_users');
		$this->db->table('medias')->emptyTable('medias_downloads');

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Creates a new File from a path File. See createFromFile().
	 *
	 * @param string $path
	 * @param array  $data Additional data to pass to insert()
	 *
	 * @return File
	 */
	public function createFromPath(string $path, array $data = []): Media
	{
		return $this->createFromFile(new CIFile($path, true), $data);
	}

	/**
	 * Creates a new File from a framework File. Adds it to the
	 * database and moves it into storage (if it is not already).
	 *
	 * @param CIFile $file
	 * @param array  $data Additional data to pass to insert()
	 *
	 * @return File
	 */
	public function createFromFile(CIFile $file, array $data = []): Media
	{
		helper('string');
		// Gather file info
		$row = [
			'filename'   => $file->getFilename(),
			'localname'  => $file->getRandomName(),
			'clientname' => $file->getFilename(),
			'type'       => $file->getMimeType(),
			'size'       => $file->getSize(),
			'ext' 		 => $file->guessExtension()
		];

		// Merge additional data
		$row = array_merge($row, $data);

		// Normalize paths
		$storage  = self::storage();
		$filePath = $file->getRealPath() ?: (string) $file;


		// Determine if we need to move the file
		if (strpos($filePath, $storage . 'original/') === false)
		{
			// Move the file
			$file = $file->move($storage . 'original/', $row['localname']);
			chmod($storage . 'original/' . $row['localname'], 0664);
		}

		// Record it in the database
		$mediaId = $this->insert($row);
		$this->addToLang($mediaId, service('LanguageOverride')->getIdLocale());

		// If a user is logged in then associate the File
		if ($userId = user()->id)
		{
			$this->addToUser($mediaId, $userId);
		}

		// Is it an image?
		if (strpos(config('Medias')->extensionImage, $row['ext' ]) === false){

			return $this->find($mediaId);
		}
		

		// Try to create a Thumbnail AND 
		$image = pathinfo($row['localname'], PATHINFO_FILENAME);
		$outputThumbnail    = $storage . 'thumbnails' . DIRECTORY_SEPARATOR . $image;
		$outputSmall    = $storage . 'small' . DIRECTORY_SEPARATOR . $image;
		$outputMedium    = $storage . 'medium' . DIRECTORY_SEPARATOR . $image;
		$outputlarge    = $storage . 'large' . DIRECTORY_SEPARATOR . $image;

		try
		 {
			service('thumbnails')->create((string) $file, $outputThumbnail);

			service('thumbnails')->setWidth(300)->setHeight(300)->create((string) $file, $outputSmall);
			service('thumbnails')->setWidth(800)->setHeight(800)->create((string) $file, $outputMedium);
			service('thumbnails')->setWidth(1024)->setHeight(1024)->create((string) $file, $outputlarge);

			// If it succeeds then update the database
			$this->update($mediaId, [
				'thumbnail' => $image,
			]);
		}
		catch (\Throwable $e)
		{
			log_message('error', $e->getMessage());
			log_message('error', 'Unable to create thumbnail for ' . $row['filename']);
		}

		// Return the File entity
		return $this->find($mediaId);
	}

	public function search(string $search){

		$result = [];

		//MEDIAS
		$fieldsMedias = $this->db->getFieldNames('medias');

		$i = 0;
		$tempArray = [];
		foreach ($fieldsMedias as $field)
		{
			$tempArray[$field] = $search;
			$i++;
		}
		
		$this->builder->orLike($tempArray);  
		$res = $this->builder->get()->getResultArray();

		$item = [];
		if(!empty($res)){
			foreach ($res as $s){
				$item[] = new $this->returnType($s);
			}
		}

		//MEDIAS LANGS
		$fieldsMediaslangs = $this->db->getFieldNames('medias_langs');

		$i = 0;
		$tempArray = [];
		foreach ($fieldsMediaslangs as $field)
		{
			$tempArray[$field] = $search;
			$i++;
		}
		
		$this->builder_langs->orLike($tempArray);  
		$res = $this->builder_langs->get()->getResult();

		$item1 = [];
		if(!empty($res)){
			foreach ($res as $s){
				$item1[] = new $this->returnType($this->builder->where('id', $s->media_id)->get()->getRowArray());
			}
		}

		$result = array_merge($item, $item1);

		return $result;

	}


}
