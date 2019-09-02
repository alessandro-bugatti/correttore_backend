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
	
	public function getTasks(Application $app, $user_id = null)
	{
		if ($user_id == null)
			$task = $app['redbean']->getAll( 'SELECT id, title, is_public FROM task');
		else
			$task = $app['redbean']->getAll( 'SELECT id, title, is_public 
							FROM task
							WHERE task.user_id = :id
							ORDER BY id DESC',
							['id' => $user_id]);
		return $task;
	}
	
	public function getTasksByTestId(Application $app, $id)
	{
		$test = $app['redbean']->load('test', $id);
		return $test->sharedTaskList;
	}
	
	public function isTaskInTest(Application $app, $task_id, $test_id)
	{
		$query = 'SELECT COUNT(*) AS n_task FROM task, task_test, test '.
			'WHERE task.id = task_test.task_id AND test.id = task_test.test_id '.
			'AND task.id = :task_id AND test.id = :test_id';
		$params = [':task_id' => $task_id, 'test_id' => $test_id];
		$task = $app['redbean']->getRow( $query, $params);
		if ($task['n_task'] == 1)
			return true;
		return false;
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
			Utility::storeTaskFile($app, $task->short_title, $files->get('description'),'description','pdf');  
			Utility::storeTaskFile($app, $task->short_title, $files->get('solution'),'solution','zip');
			Utility::storeTaskFile($app, $task->short_title, $files->get('material'),'material','zip');
     		$app['redbean']->store($task);
	    	return $task;
       	}
     	else
     		return null;
     		
	}
	
	/**
	 * Update a task
	 * @param Application $app Silex application
	 * @param array $data The fields of the task
	 * @param file $files The file bag
	 * @return object An object containing the task attributes if the record has been updated, 
	 * null otherwise
	 */
	public function updateTask(Application $app, $data, $files, $id)
	{
		$task = $app['redbean']->load( 'task', $id);
		//Does the task exist?
		if ($task->id == 0)
			return $task;
		//Is the task owned by the user?
		if($task->user_id != $app['user']->id)
			return null;
		if ($data->get("title")!=null)
			$task->title = $data->get("title");
    	if ($data->get("short_title") != null)
    		$task->short_title = $data->get("short_title");
    	if ($data->get("is_public") != null)
    		$task->is_public = $data->get("is_public");
    	if ($data->get("level") != null)
    		$task->level = $data->get("level");
    	if ($data->get("task_cases") != null)
    		$task->test_cases = $data->get("test_cases");
    	if ($data->get("category_id") != null)
    		$task->category_id = $data->get("category_id");
    	//$task->user_id = $app['user']->id;
    	//Files management
    	if ($files->has('description'))
			Utility::storeTaskFile($app, $task->short_title, $files->get('description'),'description','pdf');  
		if ($files->has('solution') )	
			Utility::storeTaskFile($app, $task->short_title, $files->get('solution'),'solution','zip');
     	if ($files->has('material'))	
     		Utility::storeTaskFile($app, $task->short_title, $files->get('material'),'material','zip');
     	$app['redbean']->store($task);
	    return $task;
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
