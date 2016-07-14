<?php

namespace Correttore\Model;

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
	
	public function getTasks(Application $app)
	{
		$task = $app['redbean']->getAll( 'SELECT id, title FROM task');
		return $task;
	}
	
}