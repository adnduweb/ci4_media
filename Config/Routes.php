<?php

if (empty(config('Medias')->routeFiles))
{
	return;
}
 
// Routes to Files controller
$routes->group(CI_AREA_ADMIN . '/medias', ['namespace' => '\Adnduweb\Ci4Media\Controllers\Admin' , 'filter' => 'login'], function ($routes)
{
	$routes->get('/', 'Medias::index');
	$routes->get('user', 'Medias::user');
	$routes->get('user/(:any)', 'Medias::user/$1');
	$routes->get('remove/(:num)',    'Medias::remove/$1');
	$routes->get('removeAll/(:num)',    'Medias::removeAll/$1');
	
	$routes->get('thumbnail/(:num)', 'Medias::thumbnail/$1');
	$routes->get('rename/(:num)', 'Medias::rename/$1');

	$routes->post('upload', 'Medias::upload');
	$routes->add('export/(:any)', 'Medias::export/$1');

	$routes->add('(:any)', 'Medias::$1');
	$routes->add('getManagerEdition', 'Medias::getManagerEdition');

	$routes->get('removedfile/(:any)', 'Medias::removeFile/$1');
	$routes->post('saveManagerEdition', 'Medias::saveManagerEdition');

});
