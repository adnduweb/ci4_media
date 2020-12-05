<?php 

namespace Adnduweb\Ci4Core\Config;

use CodeIgniter\Config\BaseConfig;

class Files extends BaseConfig
{
	/**
	 * Directory to store files (with trailing slash)
	 *
	 * @var string
	 */
	public $storagePath = WRITEPATH . 'files/';

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
		'public' => 'Adnduweb\Ci4Core\Views\layout',
		'manage' => 'Adnduweb\Ci4Core\Views\layout',
	];

	/**
	 * View file aliases
	 *
	 * @var string[]
	 */
	public $views = [
		'dropzone' => 'Adnduweb\Ci4Core\Views\Dropzone\config',
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
	public $defaultThumbnail = 'Adnduweb\Ci4Core\Assets\Unavailable.jpg';
}
