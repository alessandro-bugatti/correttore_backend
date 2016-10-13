<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class SolutionRepository{
    /**
	 * Store the solution of a task submitted from a user
	 * If the task belong to a test, also this information is stored.
	 * @param Application $app Silex application
	 * @param int $user_id The user ID
	 * @param int $task_id The task ID
	 * @param int $test_id The test ID
	 */
	
	public function storeSolution(Application $app, $submission)
	{
		//Does the solution already exist?
		//In this case the score is overwritten only if it is better than
		//the previous one
		$solution = $app['redbean']->findOne( 'solution', ' user_id = :user_id AND task_id = :task_id', 
			[ ':user_id' => $submission['user_id'], ':task_id' => $submission['task_id']] );
		//First time submission
		if ($solution == null){
			$solution = $app['redbean']->dispense('solution');
			$task = (new TaskRepository())->getTaskByID($app, $submission['task_id']);
			$solution->user_id = $submission['user_id'];
			$solution->task_id = $submission['task_id'];
			$solution->file = $task->short_title . ".cpp"; 
			$solution->score = $submission['score'];
			$solution->submitted = date('Y-m-d H:i:s');
			Utility::storeSubmittedFile($app, $app['user']->username,$submission['file'],
				$task->short_title,'cpp');
			$app['redbean']->store($solution);
		}
		//The submission already exists, but the score is ugual or better
		//than the previous one
		else if ($submission['score'] >= $solution->score)
		{
			$solution->score = $submission['score'];
			$solution->submitted = date('Y-m-d H:i:s');
			$task = (new TaskRepository())->getTaskByID($app, $submission['task_id']);
			Utility::storeSubmittedFile($app, $app['user']->username,$submission['file'],
				$task->short_title,'cpp');
			$app['redbean']->store($solution);
		}
		return $solution;    
    }
}