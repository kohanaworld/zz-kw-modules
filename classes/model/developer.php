<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Developer extends Jelly_Model {

	public static function initialize(Jelly_Meta $meta)
	{
		$meta->fields(array(
			'id'          => new Jelly_Field_Primary,
			'username'    => new Jelly_Field_String(array(
				'unique'      => TRUE,
				'allow_null'  => FALSE,
			)),
			'name'        => new Jelly_Field_String(array(
				'column'      => 'realname',
			)),
			'github_id'   => new Jelly_Field_Integer(array(
				'allow_null'       => FALSE,
			)),
			'url'         => new Jelly_Field_String(array(
				'allow_null'       => FALSE,
			)),
			'created_at'  => new Jelly_Field_Timestamp(array(
				'column'      => 'date_create',
				'allow_null'       => FALSE,
			)),
			// we dont use Jelly_Field_Email as email could be in "username [at] example.com" format
			'email'       => new Jelly_Field_String,
			'location'    => new Jelly_Field_String,
			'company'     => new Jelly_Field_String,
			'blog'        => new Jelly_Field_String(array(
				'column'      => 'blog_url',
			)),
			'info'        => new Jelly_Field_HasOne(array(
				'foreign'     => 'developer_info',
			))
		));
	}

	public function get_available(array $names)
	{
		if (empty($names))
		{
			return array();
		}
		$meta = $this->_meta;
		return DB::select('id', 'username')
				  ->from($meta->table())
				  ->where('username', 'IN', $names)
				  ->execute($meta->db())
				  ->as_array('username', 'id');
	}

	public function load_github_data($username, array $user)
	{
		$user['username'] = $username;
		$user['url'] = 'http://github.com/'.$username;
		$user['github_id'] = $user['id'];
		unset($user['id']);
		$this->set($user);

		try {
			$this->save();
		}
		catch(Validation_Exception $e)
		{
			// @TODO log errors
			echo $e->getMessage();
		}

		// update module info
		try {
			$this->info->set($user)->save();
		}
		catch(Validation_Exception $e)
		{
			// @TODO log errors
			echo $e->getMessage();
		}

		return $this;

	}
}