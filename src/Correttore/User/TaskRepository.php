<?php

namespace Correttore\User;

use Silex\Application;

class TaskRepository{
    /**
	 * 
	 */
	public function getTaskByID(Application $app, $id)
	{
		$task = $app['redbean']->load( 'task', $id);
		return $task;
	}
	
}