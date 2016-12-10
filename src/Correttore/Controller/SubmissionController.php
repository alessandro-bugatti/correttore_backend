<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Correttore\Worker\WorkerFactory;
use Correttore\Worker\Worker;
use Correttore\Model\TaskRepository;
use Correttore\Model\SolutionRepository;
use Correttore\Model\UserRepository;

class SubmissionController{
    /**
     * Receive public submissions and check the results,
     * then it returns them. If the user is registered, his code
     * is stored inside his personal folder
     * @param Application $app Silex Application
     * @param int $id The task id
     * 
     */
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
            $submission['test_id'] = null;
            $submission['file'] = $file;
            $submission['score'] = $result['score'];
            $solution->storeSolution($app,$submission);
        }
        return new JsonResponse($result,200);
    }
    
    
    public function postTestSubmission(Application $app, $test_id, $task_id)
    {
        $repo = new TaskRepository();
        $task = $repo->getTaskByID($app, $task_id);
        if ($app['user']->role->description != 'student')
            return new JsonResponse(['error' => 'Only students can submit solutions'],401);
        if ($task->id == 0)
            return new JsonResponse(['error' => 'Task does not exist'],404);
        if ($task->is_public == 1)
            return new JsonResponse(['error' => 'Task is public'],403);
        if ($repo->isTaskInTest($app, $task_id, $test_id) == false)
            return new JsonResponse(['error' => 'This task is not in the current test'],404);
        if (!$app['request']->files->has('submission'))
            return new JsonResponse(['error' => 'There is not the submitted file'],400);
        $factory = new WorkerFactory();
        $worker = $factory->createWorker($app,'cpp');
        $file = $app['request']->files->get('submission');
        $result = $worker->execute($file, $task_id);
        //Store solution in student personal folder
        $solution = new SolutionRepository();
        $submission['user_id'] = $app['user']->id;
        $submission['task_id'] = $task->id;
        $submission['test_id'] = $test_id;
        $submission['file'] = $file;
        $submission['score'] = $result['score'];
        $solution->storeSolution($app,$submission);
        return new JsonResponse($result,200);
    }
    
    /**
     * Returns the code from users submissions,
     * as a file. 
     * @param Application $app Silex Application
     * @param int $test_id The test id
     * @param int $task_id The task id
     * @param int $user_id The user id
     */
    public function getSubmission(Application $app, $test_id, $task_id, $user_id)
    {
        if ($app['user']->role->description != 'teacher')
            return new JsonResponse(['error' => 'Only teachers can submit solutions'],401);
        $repo = new SolutionRepository();
        $solution = $repo->retrieveSolution($app, $test_id, $task_id, $user_id);
        if ($solution == null)   
            return new JsonResponse(['error' => 'Solution not found'],404);
        $users = new UserRepository();
        $user = $users->getUserByID($app, $user_id);
        $source = $app['user.path'] . '/' . $user->username . '/' .
            $solution->file;
        $response = new BinaryFileResponse($source);
        return $response;
    }
}