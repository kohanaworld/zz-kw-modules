<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Jelly Model Developer
 *
 * @package   KW-Modules
 * @author	  Kohana-World Development Team
 * @license	  MIT License
 * @copyright 2011 Kohana-World Development Team
 */
class Model_Developer extends Jelly_Model {

	public static function initialize(Jelly_Meta $meta)
	{
		$meta->fields(array(
			'id'             => new Jelly_Field_Primary,
			'username'       => new Jelly_Field_String(array(
				'unique'        => TRUE,
				'allow_null'    => FALSE,
			)),
			'username_lower' => new Jelly_Field_String(array(
				'unique'        => TRUE,
				'allow_null'    => FALSE,
			)),
			'name'           => new Jelly_Field_String(array(
				'column'        => 'realname',
			)),
			'github_id'      => new Jelly_Field_Integer(array(
				'allow_null'    => FALSE,
			)),
			'url'            => new Jelly_Field_String(array(
				'allow_null'    => FALSE,
			)),
			'created_at'     => new Jelly_Field_Timestamp(array(
				'column'        => 'date_create',
				'allow_null'    => FALSE,
			)),
			// we dont use Jelly_Field_Email as email could be in "username [at] example.com" format
			'email'          => new Jelly_Field_String,
			'location'       => new Jelly_Field_String,
			'company'        => new Jelly_Field_String,
			'blog'           => new Jelly_Field_String(array(
				'column'        => 'blog_url',
			)),
			'info'           => new Jelly_Field_HasOne(array(
				'foreign'       => 'developer_info',
			)),
			'modules'        => new Jelly_Field_HasMany
		));
	}

	public function get_available(array $names)
	{
		if (empty($names))
		{
			return array();
		}

		$names = array_map('strtolower', $names);
		$meta = $this->_meta;
		return DB::select('id', 'username_lower')
				  ->from($meta->table())
				  ->where('username_lower', 'IN', $names)
				  ->execute($meta->db())
				  ->as_array('username_lower', 'id');
	}

	public function load_github_data($username, array $user)
	{
		$user['username'] = $username;
		$user['username_lower'] = strtolower($username);
		$user['url'] = 'http://github.com/'.$username;
		$user['github_id'] = $user['id'];
		unset($user['id']);
		$this->set($user);

		try {
			$this->save();
			$user['developer'] = $this->id;
		}
		catch(Validation_Exception $e)
		{
			// @TODO log errors
			echo $e->getMessage();
		}

		// update module info
		try {
			$this->info->set('developer', $this)->set($user)->save();
		}
		catch(Validation_Exception $e)
		{
			// @TODO log errors
			echo $e->getMessage();
		}

		return $this;

	}

	public function find_by_name($username)
	{
		return Jelly::query($this)->where('username', '=', $username)->limit(1)->execute($this->_meta->db());
	}

	public function get_modules($limit = NULL, $offset = NULL)
	{
		if ($limit === NULL AND $offset === NULL)
		{
			return $this->modules;
		}

		$result = $this->get('modules')->page($limit, $offset);

		return $result->select();
	}

	public function get_module_count()
	{
		return $this->get('modules')->count();
	}
}