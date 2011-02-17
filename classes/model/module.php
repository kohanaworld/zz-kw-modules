<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Jelly Model Module
 *
 * @package   KW-Modules
 * @author	  Kohana-World Development Team
 * @license	  MIT License
 * @copyright 2011 Kohana-World Development Team
 */
class Model_Module extends Jelly_Model {

	public static function initialize(Jelly_Meta $meta)
	{
		$meta->fields(array(
			'id'            => new Jelly_Field_Primary,
			'name'          => new Jelly_Field_String,
			'fullname'      => new Jelly_Field_String(array(
				'unique'            => TRUE,
				'allow_null'        => FALSE,
			)),
			'description'   => new Jelly_Field_Text,
			'url'           => new Jelly_Field_String(array(
				'allow_null'        => FALSE,
			)),
			'homepage'      => new Jelly_Field_String,
			'created_at'    => new Jelly_Field_Timestamp(array(
				'column'        => 'date_create',
			)),
			'has_wiki'      => new Jelly_Field_Boolean,
			'has_issues'    => new Jelly_Field_Boolean,
			'has_downloads' => new Jelly_Field_Boolean,
			'developer'     => new Jelly_Field_BelongsTo(array(
				'allow_null'        => TRUE,
			)),
			'info'          => new Jelly_Field_HasOne(array(
				'foreign'           => 'module_info',
			))
		));
	}

	/**
	 * @param  $data
	 * @return void
	 */
	public function process_crawler($data)
	{
		foreach($data as & $module)
		{

			$this->set($module);
			if (isset($module['id']))
			{
				$this->_original['id'] = $module['id'];
				unset($module['id']);
			}

			try {
				$this->save();
				$this->info->set($module)->set('module', $this)->save();
			}
			catch(Validation_Exception $e)
			{
				// @TODO log errors
				echo $e->getMessage();
				die();
			}

			$this->clear();
		}
	}

	/**
	 * @param array $names
	 * @return array
	 */
	public function get_available(array $names)
	{
		if (empty($names))
		{
			return array();
		}
		$meta = $this->_meta;
		return DB::select('id', 'fullname')
				  ->from($meta->table())
				  ->where('fullname', 'IN', $names)
				  ->execute($meta->db())
				  ->as_array('fullname', 'id');
	}


}