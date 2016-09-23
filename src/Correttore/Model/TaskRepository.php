<?php

namespace Correttore\Model;

use Silex\Application;
use Correttore\Util\Utility;

class TaskRepository{
	
	public function isTaskOwnedBy(Application $app, $teacher_id, $task_id)
	{
		$teacher = $app['redbean']->load( 'user', $teacher_id);
		$tasks = $teacher->ownTaskList; 
		foreach($tasks as $task)
			if ($task['id'] == $task_id)
				return true;
		return false;
	}
	
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
	
	/**
	 * Create a new task
	 * @param Application $app Silex application
	 * @param array $data The fields of the task
	 * @param file $files The file bag
	 * @return object An object containing the task attributes if the record has been created, null otherwise
	 */
	public function createTask(Application $app, $data, $files)
	{
		//Does the task title or short title already exist?
		if ($app['redbean']->findOne( 'task', ' title = ? ', [ $data->get("title") ] ) != null)
			return null;
		if ($app['redbean']->findOne( 'task', ' short_title = ? ', [ $data->get("short_title") ] ) != null)
			return null;
		$task = $app['redbean']->dispense("task");
		$task->title = $data->get("title");
    	$task->short_title = $data->get("short_title");
    	$task->is_public = $data->get("is_public");
    	$task->level = $data->get("level");
    	$task->test_cases = $data->get("test_cases");
    	$task->category_id = $data->get("category_id");
    	$task->user_id = $app['user']->id;
    	//Files management
    	if ($files->has('description') && $files->has('solution') && $files->has('material')){
			Utility::storeFile($app, $task->short_title, $files->get('description'),'description','pdf');  
			Utility::storeFile($app, $task->short_title, $files->get('solution'),'solution','zip');
			Utility::storeFile($app, $task->short_title, $files->get('material'),'material','zip');
     		$app['redbean']->store($task);
	    	return $task;
       	}
     	else
     		return null;
     		
	}
	
	public function deleteTask(Application $app, $id)
	{
		//Does the task exist?
		if (($task = $app['redbean']->load( 'task', $id )) == null)
			return false;
		Utility::rmTaskFolder($app, $task->short_title);
		$app['redbean']->trash($task);
		return true;    
    }
	
}