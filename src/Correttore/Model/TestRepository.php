<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class TestRepository{
    /**
	 * 
	 */
	public function getTests(Application $app)
	{
		$tests = $app['redbean']->find('test', 'is_on = 1');
		return Utility::BeansToArrays($tests);
	}

	public function getTestsByUserGroup(Application $app, $id)
    {
        $sql = <<<TAG
SELECT test.id, test.description, test.is_on
FROM user, test
WHERE test.user_id = user.id
    AND test.is_on = 1
    AND test.user_id IN (SELECT groupset.user_id
    FROM groupset
    WHERE id IN (SELECT groupset_user.groupset_id
          FROM groupset_user
          WHERE user_id = :student_id)
    );
TAG;
        $tests = $app['redbean']->getAll($sql, [':student_id' => $id]);
        return $tests;
    }
	
	public function getTestByID(Application $app, $id)
	{
		$test = $app['redbean']->load('test', $id);
		return $test;
	}
	
	public function getTestResults(Application $app, $test_id)
	{
		$sql = "SELECT user.id AS ID, surname, name, username, \n"
	    . "(SUM(score/task.test_cases*task_test.value)/\n"
	    . "(SELECT SUM(value) FROM task_test WHERE test_id = :test_id))*100 AS result\n"
	    . "FROM solution, user, task, task_test WHERE \n"
	    . "solution.user_id = user.id AND\n"
	    . "solution.task_id = task.id AND\n"
	    . "task_test.task_id = task.id AND\n"
	    . "task_test.test_id = :test_id AND\n"
	    . "solution.test_id = :test_id\n"
	    . "GROUP BY ID, name, surname, username\n"
	    . "ORDER BY result DESC, AVG(submitted)";
		$results = $app['redbean']->getAll( $sql, [':test_id' => $test_id]);
		return $results; 
	}
	
	public function getTestResultsByUser(Application $app, $test_id, $user_id)
	{
		$sql = "SELECT task.id AS task_id, surname, name, username, short_title, "
		. "score, test_cases, value\n"
	    . "FROM solution, user, task, task_test WHERE \n"
	    . "solution.user_id = user.id AND\n"
	    . "solution.task_id = task.id AND\n"
	    . "task_test.task_id = task.id AND\n"
	    . "task_test.test_id = :test_id AND\n"
	    . "solution.test_id = :test_id AND \n"
	    . "user.id = :user_id\n"
	    . "ORDER BY short_title";
	    $params = [':test_id' => $test_id,
	    		':user_id' => $user_id];
		$results = $app['redbean']->getAll( $sql, $params);
		return $results; 
	}
	
public function getTestResultsByGroup(Application $app, $test_id, $group_id, $with_partials)
	{
		$sql = 'SELECT A.user_id AS ID, surname, name, username, ';
	    if ($with_partials)
	    	$sql .= "GROUP_CONCAT(IFNULL(score,'-1') ORDER by A.task_id DESC SEPARATOR ',') AS partials,";
	    $sql .= <<<EOL
(SUM(score/A.test_cases*A.value)/
(SELECT SUM(value) FROM task_test WHERE test_id = :test_id))*100 AS result
FROM groupset_user, groupset, 
(
	SELECT user.id AS user_id, surname, name, username, 
    task_test.task_id AS task_id, task.test_cases AS test_cases, 
    task_test.value as value
	FROM task_test, user, task 
	WHERE task_test.test_id = :test_id AND task_test.test_id = task.id  
	ORDER by user.id) AS A 
    LEFT JOIN solution ON (A.user_id = solution.user_id AND A.task_id = solution.task_id) 
WHERE A.user_id = groupset_user.user_id AND groupset_user.groupset_id = groupset.id AND groupset.id = :group_id
GROUP BY ID,name, surname, username
ORDER BY result DESC, AVG(submitted)
EOL;
		$results = $app['redbean']->getAll( $sql, [':test_id' => $test_id,
			':group_id' => $group_id]);
		//var_dump($sql);
		return $results; 
	}
	
	public function getTestsByTeacher(Application $app, $teacher_id)
	{
		$teacher = $app['redbean']->load( 'user', $teacher_id);
		$tests = $teacher
		->with( ' ORDER BY creation_date DESC ')
		->ownTestList;
		return Utility::BeansToArrays($tests);
	}
	
	public function isTestOwnedBy(Application $app, $teacher_id, $test_id)
	{
		$tests = $this->getTestsByTeacher($app, $teacher_id);
		foreach($tests as $test)
			if ($test['id'] == $test_id)
				return true;
		return false;
	}
	
	public function createTest(Application $app, $data)
	{
		//Does the description already exist?
		if ($app['redbean']->findOne( 'test', ' description = ? ', [ $data->get("description") ] ) != null)
			return null;
		$test = $app['redbean']->dispense("test");
		$teacher = $app['redbean']->load( 'user', $app['user']->id );
		$test->description = $data->get("description");
		$test->creation_date = date('Y-m-d H:i:s');
		$test->is_on = 0; //inactive on creation
		$test->user = $teacher;
    	$app['redbean']->store($test);
	    return $test;    
    }
    
    public function updateTest(Application $app, $data, $id)
	{
		//Does the test exist?
		if (($test = $app['redbean']->load( 'test', $id)) == null)
			return null;
		//Does the description already exist in another record?
		if ($app['redbean']->findOne( 'test', ' description = ? and ID <> ?', [ $data->get("description"), $id ] ) != null)
			return null;
		$test->description = $data->get("description");
		$test->is_on = $data->get("is_on");
		$app['redbean']->store($test);
	    return $test;
    }
    
    public function deleteTest(Application $app, $data, $id)
	{
		//Does the test exist?
		if (($test = $app['redbean']->load( 'test', $id)) == null)
			return false;
		$app['redbean']->trash($test);
	    return true;
    }
    
    /**
     * Add a task to a group
     * @param Application $app Silex application
     * @param int $test_id Test id
     * @param int $task_id Task_id
     * @return boolean True if the task has been added, false otherwise
     */
    public function addTaskToTest(Application $app,$test_id,$task_id, $value)
	{
		//Does the test exist?
		if (($test = $app['redbean']->load( 'test', $test_id)) == null)
			return false;
		//Does the task exist?
		if (($task = $app['redbean']->load( 'task', $task_id)) == null)
			return false;
		$task_test = $app['redbean']->findOne( 'task_test', 
	    	' task_id = :task_id AND test_id = :test_id', 
	    	[ ':task_id' => $task_id , 'test_id' => $test_id ] );
	    //If the relationship doesn't exist, this creates it
	    if ($task_test == null)
	    {
	    	$query = 'INSERT INTO task_test (task_id,test_id) VALUES (:task_id,:test_id)';
	    	$params = [':task_id' => $task_id, 'test_id' => $test_id];
	    	$app['redbean']->exec($query, $params);
	    	$id = $app['redbean']->getInsertID();
	    	$task_test = $app['redbean']->load('task_test', $id);
	    }
	    $task_test->value = $value;
	    $app['redbean']->store($task_test);
	    return true;
    }
    
    /**
     * Remove a task from a test
     * @param Application $app Silex application
     * @param int $test_id Test id
     * @param int $task_id Task_id
     * @return boolean True if the task has been added, false otherwise
     */
    public function removeTaskFromTest(Application $app,$test_id,$task_id)
	{
		//Does the test exist?
		if (($test = $app['redbean']->load( 'test', $test_id)) == null)
			return false;
		unset($test->sharedTaskList[$task_id]);
		$app['redbean']->store($test);
	    return true;
    }
    
    public function isActive(Application $app,$test_id)
    {
    	//Does the test exist?
		if (($test = $app['redbean']->load( 'test', $test_id)) == null)
			return false;
		if ($test->is_on == 1)
			return true;
		return false;
    }
	
	
}
