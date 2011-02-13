<?php defined('SYSPATH') OR die('No direct access allowed.');

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
