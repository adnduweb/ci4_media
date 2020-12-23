<?php

namespace Adnduweb\Ci4Media\Entities;

use Michalsn\Uuid\UuidEntity;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use Config\Mimes;
use Adnduweb\Ci4Media\Structures\MediaObject;
use CodeIgniter\I18n\Time;

class Media extends UuidEntity
{
	use \Tatter\Relations\Traits\EntityTrait;
	use \Adnduweb\Ci4Core\Traits\BuilderEntityTrait;

	protected $table          = 'medias';
	protected $tableLang      = 'medias_langs';
	protected $primaryKey     = 'id';
	protected $primaryKeyLang = 'media_id';
	protected $uuids          = ['uuid'];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	/**
	 * Resolved path to the default thumbnail
	 *
	 * @var string|null
	 */
	protected static $defaultThumbnail;

	/**
	 * Resolved path to the default thumbnail
	 *
	 * @var string|null
	 */
	protected static $defaultThumbnailDoc;

	public function getIdMedia()
	{
		return $this->attributes['id'] ?? null;
	}

	public function getUuid()
	{
		return $this->attributes['uuid'] ?? null;
	}

	public function getType()
	{
		return $this->attributes['type'] ?? null;
	}

	public function getSize()
	{
		return $this->attributes['size'] ?? null;
	}

	public function getExtension()
	{
		return $this->attributes['ext'] ?? null;
	}

	public function setCreatedAt(string $dateString)
	{
		return $this->attributes['created_at'] = new Time($dateString, 'UTC');
	}

	public function getOrientation()
	{
		$pathOriginal = config('Medias')->storagePath . 'original' . DIRECTORY_SEPARATOR . ($this->attributes['localname'] ?? '');
		list($width, $height) = getimagesize($pathOriginal);
		$orientation = ( $width != $height ? ( $width > $height ? 'landscape' : 'portrait' ) : 'square' );

		return $orientation;
	}

	public function getUrlMedia()
	{
		$pathOriginal = config('Medias')->storagePath . 'original' . DIRECTORY_SEPARATOR . ($this->attributes['localname'] ?? '');
		if (!is_file($pathOriginal)) {
			$pathOriginal = self::locateDefaultThumbnail();
		}

		$pathOriginal = str_replace( ROOTPATH . 'public' , '', $pathOriginal);

		return base_url($pathOriginal);
	}


	public function nameFile()
	{
		return $this->attributes['localname'] ?? null;
	}


	public function getPath(string $dir)
	{

		$path = config('Medias')->storagePath . $dir . DIRECTORY_SEPARATOR . ($this->attributes['localname'] ?? '');

		if (!is_file($path)) {
			$path = self::locateDefaultThumbnail();
		}

		return realpath($path) ?: $path;
	}

	public function _prepareLang()
	{
		$lang = [];
		if (!empty($this->medias_langs)) {
			foreach ($this->medias_langs as $medias_langs) {
				$lang[$medias_langs->id_lang] = $medias_langs;
			}
		}
		return $lang;
	}

	public function saveLang(array $data, int $id_media)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('medias_langs');
		foreach ($data as $k => $v) {
			$medias_langs =  $builder->where(['id_lang' => $k, 'media_id' => $id_media])->get()->getRow();

			//INSERT
			if (empty($medias_langs)) {

				$data = [
                    'media_id' => $id_media,
                    'id_lang'        => $k,
                    'titre'          => $v['titre'],
                    'legende'        => $v['legende'],
                    'description'    => $v['description'],
                ];
                // Create the new participant
                if (!$builder->insert($data)) {
                    $this->error = $builder->error(true);
                }

			}else{

				$data = [
					'media_id' => $id_media,
					'id_lang'        => $k,
					'titre'          => $v['titre'],
					'legende'        => $v['legende'],
					'description'    => $v['description'],
				];
				$builder->set($data);
				$builder->where(['media_id' => $id_media, 'id_lang' => $k]);
				if (!$builder->update()) {
					$this->error = $builder->error(true);
				}
			}
			
		}
		return true;
	}

	/**
	 * Returns the absolute path to the configured default thumbnail
	 *
	 * @return string
	 *
	 * @throws FileNotFoundException
	 */
	public static function locateDefaultThumbnail(): string
	{
		// If the path has not been resolved yet then try to now
		if (is_null(self::$defaultThumbnail)) {
			$path = config('Medias')->defaultThumbnail;
			$ext  = pathinfo($path, PATHINFO_EXTENSION);

			if (!self::$defaultThumbnail = service('locator')->locateFile($path, null, $ext)) {
				throw new FileNotFoundException('Could not locate default thumbnail: ' . $path);
			}
		}

		return (string) self::$defaultThumbnail;
	}

	/**
	 * Returns the absolute path to the configured default thumbnail
	 *
	 * @return string
	 *
	 * @throws FileNotFoundException
	 */
	public static function locateDefaultVignetteDoc(): string
	{
		// If the path has not been resolved yet then try to now
		if (is_null(self::$defaultThumbnailDoc)) {
			$path = config('Medias')->defaultThumbnailDoc;
			$ext  = pathinfo($path, PATHINFO_EXTENSION);

			if (!self::$defaultThumbnailDoc = service('locator')->locateFile($path, null, $ext)) {
				throw new FileNotFoundException('Could not locate default thumbnail: ' . $path);
			}
		}

		return (string) self::$defaultThumbnailDoc;
	}

	//--------------------------------------------------------------------

	// /**
	//  * Returns the most likely actual file extension
	//  *
	//  * @param string $method Explicit method to use for determining the extension
	//  *
	//  * @return string
	//  */
	// public function getExtension($method = ''): string
	// {
	// 	if (!$method || $method === 'type') {
	// 		if ($extension = Mimes::guessExtensionFromType($this->attributes['type'])) {
	// 			return $extension;
	// 		}
	// 	}

	// 	if (!$method || $method === 'mime') {
	// 		if ($file = $this->getObject()) {
	// 			if ($extension = $file->guessExtension()) {
	// 				return $extension;
	// 			}
	// 		}
	// 	}

	// 	foreach (['clientname', 'localname', 'filename'] as $attribute) {
	// 		if (!$method || $method === $attribute) {
	// 			if ($extension = pathinfo($this->attributes[$attribute], PATHINFO_EXTENSION)) {
	// 				return $extension;
	// 			}
	// 		}
	// 	}

	// 	return '';
	// }

	/**
	 * Returns a MediaObject (CIFile/SplFileInfo) for the local file
	 *
	 * @return MediaObject|null  `null` for missing file
	 */
	public function getObject(): ?MediaObject
	{
		try {
			return new MediaObject($this->getPath(), true);
		} catch (FileNotFoundException $e) {
			return null;
		}
	}

	/**
	 * Returns class names of Exports applicable to this file's extension
	 *
	 * @param boolean $asterisk Whether to include generic "*" extensions
	 *
	 * @return string[]
	 */
	public function getExports($asterisk = true): array
	{
		$exports = [];

		if ($extension = $this->getExtension()) {
			$exports = handlers('Exports')->where(['extensions has' => $extension])->findAll();
		}

		if ($asterisk) {
			$exports = array_merge(
				$exports,
				handlers('Exports')->where(['extensions' => '*'])->findAll()
			);
		}

		return $exports;
	}

	/**
	 * Returns the path to this file's thumbnail, or the default from config.
	 * Should always return a path to a valid file to be safe for img_data()
	 *
	 * @return string
	 */
	public function getThumbnail(): string
	{
		$path = config('Medias')->storagePath . 'thumbnails' . DIRECTORY_SEPARATOR . ($this->attributes['thumbnail'] ?? '');
		$pathOriginal = config('Medias')->storagePath . 'original' . DIRECTORY_SEPARATOR . ($this->attributes['localname'] ?? '');

		if (in_array($this->getExtension(), config('Medias')->extensionDoc )) {
			$path = self::locateDefaultVignetteDoc();
		} else {
			if (!is_file($path)) {
				$path = self::locateDefaultThumbnail();
			}
		}




		return realpath($path) ?: $path;
	}


	/**
	 * Returns the path to this file's thumbnail, or the default from config.
	 * Should always return a path to a valid file to be safe for img_data()
	 *
	 * @return string
	 */
	public function getOriginal(): string
	{
		$path = config('Medias')->storagePath . 'original' . DIRECTORY_SEPARATOR . ($this->attributes['thumbnail'] ?? '');

		if (!is_file($path)) {
			$path = self::locateDefaultThumbnail();
		}

		return realpath($path) ?: $path;
	}

		/**
	 * Returns the path to this file's thumbnail, or the default from config.
	 * Should always return a path to a valid file to be safe for img_data()
	 *
	 * @return string
	 */
	public function getMedium(): string
	{
		$path = config('Medias')->storagePath . 'medium' . DIRECTORY_SEPARATOR . ($this->attributes['thumbnail'] ?? '');

		if (!is_file($path)) {
			$path = self::locateDefaultThumbnail();
		}

		return realpath($path) ?: $path;
	}
}
