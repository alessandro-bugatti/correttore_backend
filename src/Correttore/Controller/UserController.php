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
    
    public function createTeacher(Request $request, Application $app)  {
        //Check the role
        if ($request->request->get("role") != 'teacher')
            return new JsonResponse(["error", "Wrong role"], 403);
        $users = new UserRepository();
        $user = $users->createUser($app, $request->request);
        if ($user != null){
            unset($user->id);
            unset($user->password);
            unset($user->role);
            return new JsonResponse($user->export(), 201);
        }
        else
            return new Response('',409);
    }
}