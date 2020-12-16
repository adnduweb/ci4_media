<?php 

namespace Adnduweb\Ci4Media\Config;

use CodeIgniter\Config\BaseConfig;

class Medias extends BaseConfig
{
	/**
	 * Directory to store files (with trailing slash)
	 *
	 * @var string
	 */
	public $storagePath = ROOTPATH . 'public/uploads/';

	/**
	 * Whether to include routes to the Files Controller.
	 *
	 * @var boolean
	 */
	public $routeFiles = true;

	/**
	 * Layouts to use for general access and for administration
	 *
	 * @var array<string, string>
	 */
	public $layouts = [
		'public' => 'Adnduweb\Ci4Media\Views\layout',
		'manage' => 'Adnduweb\Ci4Media\Views\layout',
	];

	/**
	 * View file aliases
	 *
	 * @var string[]
	 */
	public $views = [
		'dropzone' => 'Adnduweb\Ci4Media\Views\Dropzone\config',
	];

	/**
	 * Default display format; built in are 'cards', 'list', 'select'
	 *
	 * @var string
	 */
	public $defaultFormat = 'cards';

	/**
	 * Path to the default thumbnail file
	 *
	 * @var string
	 */
	public $defaultThumbnail = 'Adnduweb\Ci4Media\Assets\Unavailable.jpg';

	public $defaultThumbnailDoc = 'Adnduweb\Ci4Media\Assets\Document.png';

	public $extensionImage =  'jpg,jpeg,png,gif,xbm,xpm,wbmp,webp,bmp';

	public $extensionDoc =  ['doc','docx','xls','csv','pdf'];

	public $extensionAllowed =  'jpg,jpeg,png,gif,xbm,xpm,wbmp,webp,bmp,doc,docs,xls,csv,pdf';
}
