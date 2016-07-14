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
    
}

