<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Jelly Model Module_Info
 *
 * @package   KW-Modules
 * @author	  Kohana-World Development Team
 * @license	  MIT License
 * @copyright 2011 Kohana-World Development Team
 */
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
					'pretty_format' => 'j M Y',
				)),
				'pushed_at'     => new Jelly_Field_Timestamp(array(
					'column'        => 'date_update_github',
					'pretty_format' => 'j M Y',
				)),
				'module'        => new Jelly_Field_BelongsTo,
			));
	}

}
