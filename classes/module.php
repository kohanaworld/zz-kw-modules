<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Module helper
 *
 * @package   KW-Core
 * @author	  Kohana-World Development Team
 * @license	  MIT License
 * @copyright 2011 Kohana-World Development Team
 */
class Module {

	public static $stats = array(
		'new'     => 0,
		'updated' => 0,
	);

	/**
	 * Get a list of github modules
	 *
	 * @static
	 * @param  string  search keyword ('kohana', 'jquery' etc)
	 * @param  string  code language ('php', 'js')
	 * @param  int     page number (offset)
	 * @return array|false
	 */
	public static function import($keyword, $lang = 'php', $page = 1)
	{
		$page = max(1, intval($page));
		$repo_api = Github::instance(TRUE)->getRepoApi();
		$data = $repo_api->search($keyword, $lang, $page);
		if (empty($data))
		{
			return FALSE;
		}

		$owners = $names = array();

		// collect owners and repo fullnames to check for existense
		foreach($data as & $module)
		{
			$module['fullname'] = $module['owner'].'/'.$module['name'];
			$names[$module['fullname']] = $module['fullname'];
			$owners[$module['owner']] = $module['owner'];
		}

		$names = Jelly::factory('module')->get_available($names);
		$owners = Developer::import($owners);

		foreach($data as & $module)
		{
			$dev_id = arr::get($owners, strtolower($module['owner']), FALSE);

			if ( ! $dev_id)
			{
				// developer not found -> delete repo
				unset($module);
				continue;
			}
			$module['developer'] = $dev_id;
			$module['fullname_lower'] = strtolower($module['fullname']);
			if ($id = arr::get($names, $module['fullname_lower'], FALSE))
			{
				// this is an existing module, so we need to update it
				$module['id'] = $id;
			}
		}

		Jelly::factory('module')->process_crawler($data);
		return TRUE;
	}

}
