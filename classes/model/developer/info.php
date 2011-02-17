<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Jelly Model Developer_Info
 *
 * @package   KW-Modules
 * @author	  Kohana-World Development Team
 * @license	  MIT License
 * @copyright 2011 Kohana-World Development Team
 */
class Model_Developer_Info extends Jelly_Model {

	public static function initialize(Jelly_Meta $meta)
	{
		$meta
			->table('developer_info')
			->fields(array(
				'id'                => new Jelly_Field_Primary,
				'followers_count'   => new Jelly_Field_Integer(array(
					'column'            => 'followers',
				)),
				'public_repo_count' => new Jelly_Field_Integer(array(
					'column'            => 'own_repos',
				)),
				'developer'         => new Jelly_Field_BelongsTo,
			));
	}

}
