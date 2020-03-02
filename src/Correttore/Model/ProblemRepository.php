<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class ProblemRepository{
    /**
     * Get the list of public problems
     * @param Application $app Silex application
     * @return Array The list of IDs and title of public problems
     */
	public function getPublicProblems(Application $app)
	{
		$publicProblems = $app['redbean']->getAll( 'SELECT id, title FROM task WHERE is_public = "1" ORDER BY id DESC' );
		return $publicProblems;
	}
	
	/**
     * Get a problem identified by id
     * @param Application $app Silex application
     * @param int $id Problem id
     * @return Array The desired problem in form of array, not bean
     */
	public function getPublicProblemByID(Application $app, $id)
	{
		$problem = $app['redbean']->getRow( 'SELECT id, title, short_title, level FROM task ' .
					'WHERE id = :id AND is_public = 1',
        	[':id' => $id]);
		return $problem;
	}
	
	/**
     * Get a problem identified by id
     * @param Application $app Silex application
     * @param int $id Problem id
     * @return Array The desired problem in form of array, not bean
     */
	public function getProblemByID(Application $app, $id)
	{
		$problem = $app['redbean']->getRow( 'SELECT id, title, short_title, level FROM task ' .
					'WHERE id = :id',
        	[':id' => $id]);
		return $problem;
	}
	
	
	/**
     * Check if a private problem is inside an active test
     * @param Application $app Silex application
     * @param int $id Problem id
     * @return boolean If the task is inside an active problem return true, 
     * false otherwise
     */
	public function isInActiveTest(Application $app, $id){
	$problem = $app['redbean']->getRow( 'SELECT task.id FROM task, test, task_test ' .
					'WHERE task.id = :id AND is_public = 0 ' . 
					'AND test.id = task_test.test_id AND '.
					'task.id = task_test.task_id AND test.is_on = 1',
        	[':id' => $id]);
        if (count($problem) == 0)
			return false;
		return true;
	}
}