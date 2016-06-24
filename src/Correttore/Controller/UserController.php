<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\User\UserRepository;

class UserController{
    
    public function getTeachers (Request $request, Application $app)  {
        $users = new UserRepository();
        $teachers = $users->getUsersByRole($app,'teacher');
        return new JsonResponse($teachers, 200);
    }
}