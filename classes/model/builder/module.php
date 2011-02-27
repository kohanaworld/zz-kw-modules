<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Builder_Module extends Jelly_Builder {

	public function page($limit = 10, $offset = 0)
	{
		if ($limit)
		{
			$this->limit((int)$limit);
		}

		if ($offset)
		{
			$this->offset((int)$offset);
		}
		return $this;
	}

}
