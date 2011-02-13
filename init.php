<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @TODO move to core as universal CLI rote
 */
if (Kohana::$is_cli)
{
	Route::set('modules_cli', 'cron/<controller>')
		->defaults(array(
			'directory' => 'cron',
	));
}
else
{
	// add another routes
}