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
		$test_id = $submission['test_id'];
		$task_id = $submission['task_id'];
		$user_id = $submission['user_id'];
		//Does the solution already exist?
		//In this case the score is overwritten only if it is better than
		//the previous one
		if ($test_id === null)
		{
			$solution = $app['redbean']->findOne( 'solution', ' user_id = :user_id AND task_id = :task_id', 
				[ ':user_id' => $user_id, ':task_id' => $task_id] );
		}
		else
		{
			$solution = $app['redbean']->findOne( 'solution', ' user_id = :user_id AND task_id = :task_id AND test_id = :test_id', 
				[ ':user_id' => $user_id, ':task_id' => $task_id,
					':test_id' => $test_id] );
		}
		$task = (new TaskRepository())->getTaskByID($app, $task_id);
		$file_name = $task->short_title . 
				(($test_id === null)?'':'_test_' . $test_id);
		//First time submission
		if ($solution == null){
			$solution = $app['redbean']->dispense('solution');
			$solution->user_id = $user_id;
			$solution->task_id = $task_id;
			$solution->test_id = $test_id;
			$solution->file =  $file_name . '.cpp';
			$solution->score = $submission['score'];
			$solution->submitted = date('Y-m-d H:i:s');
			Utility::storeSubmittedFile($app, $app['user']->username,$submission['file'],
				$file_name,'cpp');
			$app['redbean']->store($solution);
		}
		//The submission already exists, but the score is ugual or better
		//than the previous one
		else if ($submission['score'] >= $solution->score)
		{
			$solution->score = $submission['score'];
			$solution->submitted = date('Y-m-d H:i:s');
			Utility::storeSubmittedFile($app, $app['user']->username,$submission['file'],
				$file_name,'cpp');
			$app['redbean']->store($solution);
		}
		return $solution;    
    }
    
    public function retrieveSolution(Application $app, $test_id, $task_id, $user_id)
	{
		$solution = $app['redbean']->findOne( 'solution', ' user_id = :user_id AND task_id = :task_id AND test_id = :test_id', 
				[ ':user_id' => $user_id, ':task_id' => $task_id,
					':test_id' => $test_id] );    
		return $solution;
    }
}