<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Module extends Controller_Template {

	public function action_index()
	{
		$developer = $this->request->param('developer');
		if ( ! $developer )
		{
			// show module list
			return $this->show_modules();
		}

		$module = $this->request->param('module');
		if ( ! $module )
		{
			// show developer info
			return $this->show_developer($developer);
		}

		// show module by name applied
		return $this->show_module($developer.'/'.$module);
	}

	/**
	 * Display module list
	 *
	 * @return void
	 */
	public function show_modules()
	{
		$page = max(1, arr::get($_GET, 'page', 1));
		// @TODO move to config
		$count = 10;
		$offset = ($page - 1)*$count;
		$modules = Jelly::factory('module')->get_modules($count, $offset);
		$module_count = Jelly::factory('module')->get_count();
		$pagination = Pagination::factory(array(
			'total_items'  => $module_count,
			'view'         => 'pagination/floating',
		));
		$this->template->content = View::factory('frontend/module/list')->set('modules', $modules)->set('pagination', $pagination);
	}

	/**
	 * Display module info
	 *
	 * @throws Http_Exception_404
	 * @param  string $name
	 * @return void
	 */
	public function show_module($name)
	{
		$module = Jelly::factory('module')->find_by_fullname($name);
		if ( ! $module->loaded() )
		{
			throw new Http_Exception_404(__('module :name not found'), array(':name' => $name));
		}

		$this->template->content = View::factory('frontend/module/profile')->set('module', $module);
	}

	/**
	 * Display developer info (with modules)
	 *
	 * @throws Http_Exception_404
	 * @param  string $username
	 * @return void
	 */
	public function show_developer($username)
	{
		$developer = Jelly::factory('developer')->find_by_name($username);
		if ( ! $developer->loaded() )
		{
			throw new Http_Exception_404(__('developer :name not found'), array(':name' => $username));
		}

		$page = max(1, arr::get($_GET, 'page', 1));
		// @TODO move to config
		$count = 10;
		$offset = ($page - 1)*$count;

		$module_count = $developer->get_module_count();

		$pagination = Pagination::factory(array(
			'total_items'  => $module_count,
			'view'         => 'pagination/floating',
		));

		$modules = $developer->get_modules($count, $offset);

		$this->template->content = View::factory('frontend/developer/profile')
			->set('developer', $developer)
			->set('modules', $modules)
			->set('pagination', $pagination);
	}

}
