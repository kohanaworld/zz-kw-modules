<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Module_Info extends Jelly_Model {

	public static function initialize(Jelly_Meta $meta)
	{
		$meta
			->table('module_info')
			->fields(array(
				'id'            => new Jelly_Field_Primary,
				'forks'         => new Jelly_Field_Integer(array(
					'default'       => 0,
				)),
				'watchers'      => new Jelly_Field_Integer(array(
					'default'       => 0,
				)),
				'tags'          => new Jelly_Field_Integer(array(
					'default'       => 0,
				)),
				'score'         => new Jelly_Field_Float(array(
					'default'       => 0,
				)),
				'open_issues'   => new Jelly_Field_Integer(array(
					'column'        => 'issues_opened',
					'default'       => 0,
				)),
				'issues_closed' => new Jelly_Field_Integer(array(
					'default'       => 0,
				)),
				'date_update'   => new Jelly_Field_Timestamp(array(
					'auto_now_update' => TRUE,
					'auto_now_create' => TRUE,
				)),
				'pushed_at'     => new Jelly_Field_Timestamp(array(
					'column'        => 'date_update_github',
				)),
				'module'        => new Jelly_Field_BelongsTo,
			));
	}

}
