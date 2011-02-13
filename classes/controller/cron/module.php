<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Cron_Module extends Controller_Cron {

	/**
	 * Default action. Managing module scanning, explore new developers if needed
	 * @return void
	 */
	public function action_index()
	{
		$started = time();
		$crawler = Model::factory('crawler')->type('module');
		$status = $crawler->get_status(TRUE);
		if ($status === FALSE)
		{
			// @TODO throw exception? log error?
			return;
		}

		$page = arr::get($status, 'page', 1);
		$stop = arr::get($status, 'stopped', 0);

		while ( ! $stop)
		{
			try {
				$stop = FALSE === Module::import('kohana', 'php', $page);
				if (time() - $started > 60*4)
				{
					// limit execution time for ~4 minutes
					echo 'time limit!'.PHP_EOL;
					$stop = TRUE;
				}
			}
			catch(phpGitHubApiRequestException $e)
			{
				echo 'github exception ('.$e->getMessage().'), code#'.$e->getCode().PHP_EOL;
				$stop = TRUE;
			}

			$crawler->update_status($page, $stop);
			echo 'page #'.$page.' scanned'.PHP_EOL;
			$page ++;
		}
	}

}