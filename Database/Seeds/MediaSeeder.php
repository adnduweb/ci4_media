<?php namespace Adnduweb\Ci4Media\Database\Seeds;

use Adnduweb\Ci4Core\Models\LanguageModel;
use Adnduweb\Ci4Core\Models\TabModel;
use Adnduweb\Ci4Core\Models\SettingModel;

class MediaSeeder extends \CodeIgniter\Database\Seeder
{
	public function run()
	{
		$templates = [
			[
				'name'      => 'perPage',
				'scope'     => 'user',
				'content'   => '8',
				'summary'   => 'Number of items to show per page',
				'protected' => 1,
			],
			[
				'name'      => 'filesFormat',
				'scope'     => 'user',
				'content'   => 'cards',
				'protected' => 0,
				'summary'   => 'Display format for listing files',
			],
			[
				'name'      => 'filesSort',
				'scope'     => 'user',
				'content'   => 'filename',
				'protected' => 0,
				'summary'   => 'Sort field for listing files',
			],
			[
				'name'      => 'filesOrder',
				'scope'     => 'user',
				'content'   => 'asc',
				'protected' => 0,
				'summary'   => 'Sort order for listing files',
			],
		];

		// Check for each template and create it if it is missing
		foreach ($templates as $template)
		{
			if (! model(SettingModel::class)->where('name', $template['name'])->first())
			{
				model(SettingModel::class)->insert($template);
			}
		}

		// Get item menu
		// $tabBO = array(
		// 	'media'        => array('en' => 'Image Manager', 'fr' => 'médias')
		// );
		
		
		// // Création du menu en BO
		// $rows = [
		// 	[
		// 		'id'        => 18,
		// 		'id_parent' => 17,
		// 		'depth'     => 2,
		// 		'left'      => 13,
		// 		'right'     => 14,
		// 		'position'  => 3,
		// 		'section'   => 0,
		// 		'module'    => NULL,
		// 		'namespace' => 'App\Controllers\Admin',
		// 		'class_name'      => 'media',
		// 		'active'    => 1,
		// 		'icon'      => '',
		// 		'slug'      => 'medias',
		// 	],
		// ];

		// // Check for and create project langue templates
		// $tab = new TabModel();
		// $db = \Config\Database::connect();
		// $languages = new languageModel();
		// $langues = $languages->get()->getResult();
		// foreach ($rows as $row) {
		// 	$tabRow = $tab->where('class_name', $row['class_name'])->first();

		// 	if (empty($tabRow)) {
		// 		// No langue - add the row
		// 		//print_r($row); exit;
		// 		$tab->insert($row);
		// 		$i = 0;
		// 		$newInsert = $tab->insertID();
		// 		foreach ($langues as $langue) {
		// 			$rowsLang[$i]['tab_id']   = $newInsert;
		// 			$rowsLang[$i]['id_lang']  = $langue->id;
		// 			$rowsLang[$i]['name']     =  $tabBO[$row['class_name']][$langue->iso_code];
		// 			$i++;
		// 		}
		// 		//print_r($rowsLang); exit;
		// 		foreach ($rowsLang as $rowLang) {
		// 			$db->table('tabs_langs')->insert($rowLang);
		// 		}
		// 	}
		// }

	}
}