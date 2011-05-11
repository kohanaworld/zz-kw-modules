<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @TODO move to core as universal CLI rote
 */
Route::set('modules_cli', 'cron/<controller>')
	->defaults(array(
		'directory' => 'cron',
	));

Route::set('modules', 'modules(/<developer>(/<module>(/<action>)))', array('module' => '[a-zA-Z0-9_\-\.]+'))
	->defaults(array(
		'controller' => 'module',
	));

/*Route::set('github_links', "<developer>(/<module>)(/<section>)")
	->defaults(array(
		'host'      => 'https://github.com',
	));*/