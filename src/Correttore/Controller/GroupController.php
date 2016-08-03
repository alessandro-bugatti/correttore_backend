<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\GroupRepository;
use Correttore\Model\UserRepository;

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
                return new JsonResponse(['error'=>'group already exist'], 409);
            return new JsonResponse($group->export(),200);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function updateGroup (Request $request, Application $app, $id)  {
        $groupRep = new GroupRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this group owned by the teacher?
            if (!$groupRep->isGroupOwnedBy($app, $app['user']->id, $id))
                return new JsonResponse(['error'=>"permission denied, user does not own this group"], 401);
            $group = $groupRep->updateGroup($app, $request->request, $id);
            if ($group == null)
                return new JsonResponse(['error'=>"group does not exist or description is duplicated"], 403);
            return new JsonResponse($group->export(),200);
        }
        else
            return new JsonResponse('',401);
    }
    
    public function deleteGroup (Request $request, Application $app, $id)  {
        $groupRep = new GroupRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this group owned by the teacher?
            if (!$groupRep->isGroupOwnedBy($app, $app['user']->id, $id))
                return new JsonResponse(['error'=>"permission denied, user does not own this group or the group does not exist"], 401);
            if ($groupRep->deleteGroup($app, $request->request, $id) == true)
                return new JsonResponse('',204);
            return new JsonResponse('',404);
        }
        else
            return new JsonResponse('',401);
    }   
    
    /**
     * Add a student to a group
     * @param Application $app Silex application
     * @param int $group_id Group id
     * @param int $user_id User_id
     * @return JsonResponse The JSON response
     */
    public function addUserToGroup (Application $app, $group_id, $user_id)  {
        $groupRep = new GroupRepository();
        $userRep = new UserRepository();
        //Check the role
        if ($app['user']->role->description == 'teacher'){
            //Is this group owned by the teacher?
            if (!$groupRep->isGroupOwnedBy($app, $app['user']->id, $group_id))
                return new JsonResponse(['error'=>"permission denied, user does not own this group or the group does not exist"], 401);
            //Does the user is a student or does the student exist?
            if (!$userRep->isStudent($app,$user_id))
                return new JsonResponse(['error'=>"permission denied, user is not a student or the student does not exist"], 401);
            if ($groupRep->addUserToGroup($app, $group_id, $user_id) == true)
                return new JsonResponse('',204);
            return new JsonResponse('',404);
        }
        else
            return new JsonResponse('',401);
    }   
    
    
}