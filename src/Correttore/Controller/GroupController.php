<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\GroupRepository;

class GroupController{
    
    public function getGroups(Application $app)
    {
        $groupRep = new GroupRepository();
        if ($app['user']->role->description == 'admin'){
            $groups = $groupRep->getGroups($app);
            return new JsonResponse($groups,200);
        }
        else if ($app['user']->role->description == 'teacher'){
            $groups = $groupRep->getGroupsByTeacher($app, $app['user']->id);
            return new JsonResponse($groups,200);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function createGroup(Request $request, Application $app)  {
        $groupRep = new GroupRepository();
        if ($app['user']->role->description == 'teacher'){
            $group = $groupRep->createGroup($app, $request->request);
            if ($group == null)
                return new JsonResponse(['error'=>'group already exist'], 403);
            return new JsonResponse($group->export(),200);
        }
        else
            return new JsonResponse('',401);
    }
    
}