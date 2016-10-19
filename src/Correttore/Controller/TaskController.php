<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\TaskRepository;
use Correttore\Model\TestRepository;
use Correttore\Util\Utility;


class TaskController{

    public function getTask (Application $app, $id)  {
        $tasks = new TaskRepository();
        $task = $tasks->getTaskByID($app, $id);
        if ($task->ID == 0)
            return new Response('', 404);
        return new JsonResponse($task->export(), 200);
    }
    
    public function getTasks (Application $app)  {
        $tasksRep = new TaskRepository();
        $tasks = $tasksRep->getTasks($app);
        return new JsonResponse($tasks, 200);
    }
    
    public function getTasksByTestId (Application $app, $id)  {
        $tasksRep = new TaskRepository();
        if ($app['user']->role->description == 'student')
        {
            $tests = new TestRepository();
            $test = $tests->getTestByID($app, $id);
            if ($test->id != null && $test->is_on == 1)
            {
                $tasks = $tasksRep->getTasksByTestId($app, $id);
                $tasks = Utility::BeansToArrays($tasks);
                Utility::RemoveFieldsFromArrays($tasks, ['short_title','is_public','level','test_cases','category_id', 'user_id']);
                return new JsonResponse($tasks, 200);
            }
            else return new JsonResponse('',404);
        }
        else if ($app['user']->role->description == 'teacher')
        {
            $tests = new TestRepository();
            if ($tests->isTestOwnedBy($app,$app['user']->id, $id))
            {
                $tasks = $tasksRep->getTasksByTestId($app, $id);
                $tasks = Utility::BeansToArrays($tasks);
                Utility::RemoveFieldsFromArrays($tasks, ['short_title','is_public','level','test_cases','category_id', 'user_id']);
                return new JsonResponse($tasks, 200);
            }
            else return new JsonResponse('',404);
        }
        else return new JsonResponse('',401);
    }
    
    public function createTask (Application $app, Request $request)
    {
        $tasksRep = new TaskRepository();
        $task = $tasksRep->createTask($app, $request->request, $request->files);
        if ($task != null)
            return new JsonResponse($task->export(), 200);
        else
            return new JsonResponse('',409);
    }
    
    public function updateTask (Application $app, Request $request, $id)
    {
        $tasksRep = new TaskRepository();
        $task = $tasksRep->updateTask($app, $request->request, $request->files, $id);
        if ($task == null)
            return new JsonResponse(["error" => "The user doesn't own the task"],409);
        else if ($task->id == 0)
            return new JsonResponse(["error" => "The task doesn't exist"],409);
        else    
            return new JsonResponse($task->export(), 202);
    }
    
    public function deleteTask (Application $app, $id)  {
        $tasks = new TaskRepository();
        $task = $tasks->getTaskByID($app,$id);
        if ($task->ID == 0)
            return new JsonResponse('', 404);
        if ($app['user']->role->description != 'teacher')
            return new JsonResponse(['error'=>'Method not allowed by your role'], 403);
        if (!$tasks->isTaskOwnedBy($app,$app['user']->id,$task->id))
            return new JsonResponse(['error'=>'You don\'t own the task'], 403);
        if ($tasks->deleteTask($app, $id))
            return new JsonResponse('',200);
        return new JsonResponse('',404);
    }
}

