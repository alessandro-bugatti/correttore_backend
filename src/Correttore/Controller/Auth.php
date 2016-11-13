<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\UserRepository;

class Auth{
    /**
    *  Method to manage login
    * \param $request The HTTP request
    * \param $app The Silex application
    */
    public function login (Request $request, Application $app)  {
        if (null !== $request->request->get('username'))
        {
            $users = new UserRepository();
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $user = $users->getAuthenticatedUser($app,$username,$password);
        }
        else
            return new JsonResponse(['error'=>'Not a valid request'], 403);
        if ($user != null ){
            $response['id'] = $user->id;
            $response['token'] = $user->token;
            $response['username'] = $user->username;
            $response['role'] = $user->role->description;
            return new JsonResponse($response, 200); 
        }
        else
            return new JsonResponse(['error'=>'User not found'], 403);    
    }

    public function info(Request $request, Application $app)  {
        $user = $app['user'];
        if ($user == null)
            return new JsonResponse(['error'=>'Forbidden'], 403);
        $response['id'] = $user->id;
        $response['token'] = $user->token;
        $response['username'] = $user->username;
        $response['role'] = $user->role->description;
        return new JsonResponse($response, 200); 
    }
    
    public function logout(Request $request, Application $app)  {
        $user = $app['user'];
        if ($user == null)
            return new JsonResponse(['error'=>'Forbidden'], 403);
        $users = new UserRepository();
        $users->clearTokenByUsername($app, $user->username);
        return new Response('',200); 
    }

}