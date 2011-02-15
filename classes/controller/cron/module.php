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
			// smgladkovskiy: DB log table record + email notification, I think.
			return;
		}

		$page = arr::get($status, 'page', 1);
		$stop = arr::get($status, 'stopped', 0);
		// a reason for stopping cron execution
		// @TODO define constant values? something like Cron::END_NORMAL or Cron::END_TIMELIMIT
		$reason = 'end of search';
		$level = 'info';

		while ( ! $stop)
		{
			sleep(1);
			try {
				$stop = FALSE === Module::import('kohana', 'php', $page);
				if ( ! Kohana::$is_cli AND (time() - $started) > 60*4)
				{
					// limit execution time for ~4 minutes
					echo 'time limit!'.PHP_EOL;
					$stop = TRUE;
					$reason = 'end of time limit';
					$level = 'notice';
				}
			}
			catch(phpGitHubApiRequestException $e)
			{
				echo 'github exception ('.$e->getMessage().'), code#'.$e->getCode().PHP_EOL;
				$stop = TRUE;
				$reason = 'github limitation';
				$level = 'notice';
			}

			$crawler->update_status($page, $stop);
			echo 'page #'.$page.' scanned'.PHP_EOL;
			$page ++;
		}

		try {
			Jelly::factory('log')->set(array(
				'executant'    => 'crawler_module',
				'action'       => 'search',
				'text'         => 'search complete ('.$reason.')',
				'level'        => $level,
			))->save();
		}
		catch( Validation_Exception $e)
		{
			Kohana::$log->add('error', 'unable to save cron log: '.$e->getMessage());
		}
	}

}