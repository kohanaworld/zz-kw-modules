<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Developer helper
 *
 * @package   KW-Core
 * @author	  Kohana-World Development Team
 * @license	  MIT License
 * @copyright 2011 Kohana-World Development Team
 */
class Developer {

	public static $stats = array(
		'new'     => 0,
		'updated' => 0,
	);

	/**
	 * Check usernames applied for existence. New devs will be loaded from github and saved
	 *
	 * @static
	 * @param   array  developer usernames
	 * @return  array  developer ids
	 */
	public static function import(array $names)
	{
		$exist = Jelly::factory('developer')->get_available($names);

		foreach($names as $username)
		{
			if (isset($exist[strtolower($username)]))
			{
				continue;
			}

			// search develop
			$id = Developer::load($username);
			$exist[$username] = $id;
		}

		return $exist;
	}

	public static function load($username)
	{
		$user_api = Github::instance(TRUE)->getUserApi();
		$user = $user_api->show($username);
		// set 'username' field
		$dev = Jelly::factory('developer')->load_github_data($username, $user);

		return $dev->id();
	}

}
