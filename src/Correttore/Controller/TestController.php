<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\TestRepository;
use Correttore\Model\UserRepository;
use Correttore\Model\TaskRepository;

class TestController{
    
    public function getTests(Application $app)
    {
        $testsRep = new TestRepository();
        if ($app['user']->role->description == 'teacher'){
            $tests = $testsRep->getTestsByTeacher($app, $app['user']->id);
            return new JsonResponse($tests,200);
        }
        else if ($app['user']->role->description == 'student'){
            $tests = $testsRep->getTests($app);
            return new JsonResponse($tests,200);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function getTestResults(Application $app, $test_id)
    {
        $testsRep = new TestRepository();
        //var_dump($app['user']);
        if ($app['user']->role->description == 'teacher'){
            //Is this test owned by the teacher?
            if (!$testsRep->isTestOwnedBy($app, $app['user']->id, $test_id))
                return new JsonResponse(['error'=>"permission denied, user does not own this test"], 401);
            $testResults = $testsRep->getTestResults($app, $test_id);
            return new JsonResponse($testResults,200);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function createTest(Request $request, Application $app)  {
        $testRep = new testRepository();
        if ($app['user']->role->description == 'teacher'){
            $test = $testRep->createTest($app, $request->request);
            if ($test == null)
                return new JsonResponse(['error'=>'test already exist'], 409);
            return new JsonResponse($test->export(),201);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function updateTest (Request $request, Application $app, $id)  {
        $testRep = new TestRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this test owned by the teacher?
            if (!$testRep->isTestOwnedBy($app, $app['user']->id, $id))
                return new JsonResponse(['error'=>"permission denied, user does not own this test"], 401);
            $test = $testRep->updateTest($app, $request->request, $id);
            if ($test == null)
                return new JsonResponse(['error'=>"test does not exist or description is duplicated"], 403);
            return new JsonResponse($test->export(),200);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function deleteTest (Request $request, Application $app, $id)  {
        $testRep = new TestRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this test owned by the teacher?
            if (!$testRep->isTestOwnedBy($app, $app['user']->id, $id))
                return new JsonResponse(['error'=>"permission denied, user does not own this test or the test does not exist"], 401);
            if ($testRep->deleteTest($app, $request->request, $id) == true)
                return new JsonResponse('',204);
            return new JsonResponse('',404);
        }
        else
            return new JsonResponse('',401);
    }   
    
    /**
     * Add a task to a test
     * @param Application $app Silex application
     * @param int $test_id Test id
     * @param int $task_id Task_id
     * @return JsonResponse The JSON response
     */
    public function addTaskToTest (Application $app, $test_id, $task_id)  {
        $testRep = new TestRepository();
        $taskRep = new TaskRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this test owned by the teacher?
            if (!$testRep->isTestOwnedBy($app, $app['user']->id, $test_id))
                return new JsonResponse(['error'=>"permission denied, user does not own this test or the test does not exist"], 401);
            //Does the task is not public or does the task exist?
            $task = $taskRep->getTaskByID($app, $task_id);
            if ($task->id == 0 || $task->is_public)
			    return new JsonResponse(['error'=>"permission denied, task is public or the task does not exist"], 401);
            if ($testRep->addTaskToTest($app, $test_id, $task_id) == true)
                return new JsonResponse('',204);
            return new JsonResponse('',404);
        }
        else
            return new JsonResponse('',401);
    }   
    
    /**
     * Remove a task from a test
     * @param Application $app Silex application
     * @param int $test_id Test id
     * @param int $task_id Task_id
     * @return boolean True if the task has been added, false otherwise
     */
    public function removeTaskFromTest (Application $app, $test_id, $task_id)  {
        $testRep = new TestRepository();
        $taskRep = new TaskRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this group owned by the teacher?
            if (!$testRep->isTestOwnedBy($app, $app['user']->id, $test_id))
                return new JsonResponse(['error'=>"permission denied, user does not own this test or the test does not exist"], 401);
            //Does the task exist?
            $task = $taskRep->getTaskByID($app, $task_id);
            if ($task->id == 0 || $task->is_public)
			    return new JsonResponse(['error'=>"permission denied, the task does not exist"], 401);
			if ($testRep->removeTaskFromTest($app, $test_id, $task_id) == true)
                return new JsonResponse('',204);
            return new JsonResponse('',404);
        }
        else
            return new JsonResponse('',401);
    }   
    
    
}