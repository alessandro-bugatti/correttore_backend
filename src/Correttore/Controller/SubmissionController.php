<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Worker\WorkerFactory;
use Correttore\Worker\Worker;
use Correttore\Model\TaskRepository;
use Correttore\Model\SolutionRepository;

class SubmissionController{
    
    public function postPublicSubmission(Application $app, $id)
    {
        $repo = new TaskRepository();
        $task = $repo->getTaskByID($app, $id);
        if ($task->id == 0)
            return new JsonResponse(['error' => 'Task does not exist'],404);
        if ($task->is_public == 0)
            return new JsonResponse(['error' => 'Task is not public'],403);
        if (!$app['request']->files->has('submission'))
            return new JsonResponse(['error' => 'There is not the submitted file'],400);
        $factory = new WorkerFactory();
        $worker = $factory->createWorker($app,'cpp');
        $file = $app['request']->files->get('submission');
        $result = $worker->execute($file, $id);
        //If the user is authenticated
        if ($app['user'] != null)
        {
            $solution = new SolutionRepository();
            $submission['user_id'] = $app['user']->id;
            $submission['task_id'] = $task->id;
            $submission['file'] = $file;
            $submission['score'] = $result['score'];
            $solution->storeSolution($app,$submission);
        }
        return new JsonResponse($result,200);
    }
    
}