<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\TaskRepository;


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

