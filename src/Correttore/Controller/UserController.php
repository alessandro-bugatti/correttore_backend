<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\UserRepository;

class UserController{
    
    private function createUser(Request $request, Application $app)
    {
        $users = new UserRepository();
        $user = $users->createUser($app, $request->request);
        if ($user != null){
            unset($user->id);
            unset($user->password);
            unset($user->role);
            unset($user->role_id);
            return new JsonResponse($user->export(), 201);
        }
        else
            return new Response('',409);
    }
    
    private function updateUser(Request $request, Application $app, $id)
    {
        $users = new UserRepository();
        $user = $users->updateUser($app, $request->request, $id);
        if ($user != null){
            unset($user->id);
            unset($user->password);
            unset($user->role);
            unset($user->role_id);
            return new JsonResponse($user->export(), 200);
        }
        else 
            return new Response('',409);
    }
    
    private function deleteUser(Application $app, $id)
    {
        $users = new UserRepository();
        if ($users->deleteUser($app, $id) == true)
            return new Response('', 204);
        else
            return new Response('', 404);
    }
    
    
    
    private function getUserByIDRole(Application $app, $id, $role)
    {
        $users = new UserRepository();
        $user = $users->getUserByID($app,$id);
        if ($user->ID == 0)
            return new Response('', 404);
        if ($user->role->description != $role)
            return new Response('', 404);
        unset($user->password);
        unset($user->role);
        unset($user->role_id);
        unset($user->token);
        return new JsonResponse($user->export(), 200);
    }
    
    /*
    *   TEACHERS
    */
    public function getTeachers (Application $app)  {
        $users = new UserRepository();
        $teachers = $users->getUsersByRole($app,'teacher');
        return new JsonResponse($teachers, 200);
    }
    
    public function createTeacher(Request $request, Application $app)  {
        //Check the role
        if ($request->request->get("role") != 'teacher')
            return new JsonResponse(["error", "Wrong role"], 403);
        return $this->createUser($request, $app);
    }
    
    public function getTeacher (Application $app, $id)  {
        return $this->getUserByIDRole($app,$id,'teacher');
    }
    
    public function updateTeacher (Request $request, Application $app, $id)  {
        //Check the role
        if ($request->request->get("role") != 'teacher')
            return new JsonResponse(["error", "Wrong role"], 403);
        return $this->updateUser($request, $app, $id);
    }
    
    public function deleteTeacher (Application $app, $id)  {
        $users = new UserRepository();
        $user = $users->getUserByID($app,$id);
        if ($user->ID == 0)
            return new Response('', 404);
        if ($user->role->description != 'teacher')
            return new Response('', 403);
        return $this->deleteUser($app, $id);
    }
}