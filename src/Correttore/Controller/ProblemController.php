<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Correttore\Model\ProblemRepository;
use Correttore\Model\UserRepository;
use Correttore\Model\TaskRepository;

class ProblemController{
    
    public function getPublicProblems(Application $app)
    {
        $problemRep = new ProblemRepository();
        $problems = $problemRep->getPublicProblems($app);
        return new JsonResponse($problems,200);
    }
    
    public function getPublicProblem (Application $app, $id)  {
        $problems = new ProblemRepository();
        $problem = $problems->getPublicProblemByID($app, $id);
        if ($problem['id'] == 0)
            return new JsonResponse(['error' => "Problem doesn't exist or it is not public"], 404);
        return new JsonResponse($problem, 200);
    }
    
    public function getPublicProblemPDF (Application $app, $id)  {
        $problems = new ProblemRepository();
        $problem = $problems->getPublicProblemByID($app, $id);
        if ($problem['id'] != 0){
            $pdf = $app['task.path'] . $problem['short_title'] . '/description.pdf';
            if ( !file_exists($pdf))
                return new JsonResponse(['error' => "PDF not found"], 404);
            $response = new BinaryFileResponse($pdf);
            return $response;
        }
        else
            return new JsonResponse(['error' => "Problem doesn't exist or it is not public"], 404);
    }
    
    public function getProblemPDF (Application $app, $id)  {
        $problems = new ProblemRepository();
        $tasks = new TaskRepository();
        if ($app['user']->role->description == 'teacher' &&  
            !$tasks->isTaskOwnedBy($app, $app['user']->id, $id))
            return new JsonResponse(['error' => "Only owner can see the task"], 403);
        if (!$problems->isInActiveTest($app, $id) &&
            $app['user']->role->description == 'student')
            return new JsonResponse(['error' => "The problem is not actually active"], 404);
        $problem = $problems->getProblemByID($app, $id);
        if ($problem['id'] != 0){
            $pdf = $app['task.path'] . $problem['short_title'] . '/description.pdf';
            if ( !file_exists($pdf))
                return new JsonResponse(['error' => "PDF not found"], 404);
            $response = new BinaryFileResponse($pdf);
            return $response;
        }
        else
            return new JsonResponse(['error' => "Problem doesn't exist or it is not public"], 404);
    }
    
    public function getTestProblem (Application $app, $id)  {
        $problems = new ProblemRepository();
        if ($app['user']->role->description != 'student')
            return new JsonResponse(['error' => "Only students can get a private problem"], 403);
        if (!$problems->isInActiveTest($app, $id))
            return new JsonResponse(['error' => "The problem is not actually active"], 404);
        $problem = $problems->getProblemByID($app, $id);
        if ($problem['id'] == 0)
            return new JsonResponse(['error' => "Problem doesn't exist or it is not public"], 404);
        return new JsonResponse($problem, 200);
    }
}