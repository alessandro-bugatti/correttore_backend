<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\ProblemRepository;
use Correttore\Model\UserRepository;

class ProblemController{
    
    public function getPublicProblems(Application $app)
    {
        $problemRep = new ProblemRepository();
        $problems = $problemRep->getPublicProblems($app);
        return new JsonResponse($problems,200);
    }
    
}