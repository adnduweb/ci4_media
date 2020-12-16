<?php 

namespace Adnduweb\Ci4Media\Config;

/**
 * Class Registrar
 *
 * Provides a basic registrar class for testing BaseConfig registration functions.
 */

class Registrar
{
	/**
	 * Override database config
	 *
	 * @return array
	 */
	public static function Pager()
	{
		return [
			'templates' => [
				'files_bootstrap' => 'Adnduweb\Ci4Media\Views\pager',
			],
		];
	}
}
