<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class GroupRepository{
    /**
	 * 
	 */
	public function getGroups(Application $app)
	{
		$groups = $app['redbean']->findAll( 'groupset');
		return Utility::BeansToArrays($groups);
	}
	
	public function getGroupsByTeacher(Application $app, $teacher_id)
	{
		$teacher = $app['redbean']->load( 'user', $teacher_id);
		$groups = $teacher->ownGroupsetList;
		return Utility::BeansToArrays($groups);
	}
	
	public function isGroupOwnedBy(Application $app, $teacher_id, $group_id)
	{
		$groups = $this->getGroupsByTeacher($app, $teacher_id);
		foreach($groups as $group)
			if ($group['id'] == $group_id)
				return true;
		return false;
	}
	
	public function createGroup(Application $app, $data)
	{
		//Does the description already exist?
		if ($app['redbean']->findOne( 'groupset', ' description = ? ', [ $data->get("description") ] ) != null)
			return null;
		$group = $app['redbean']->dispense("groupset");
		$teacher = $app['redbean']->load( 'user', $app['user']->id );
		$group->description = $data->get("description");
		$group->user = $teacher;
    	$app['redbean']->store($group);
	    return $group;    
    }
    
    public function updateGroup(Application $app, $data, $id)
	{
		//Does the group exist?
		if (($group = $app['redbean']->load( 'groupset', $id)) == null)
			return null;
		//Does the description already exist in another record?
		if ($app['redbean']->findOne( 'groupset', ' description = ? and ID <> ?', [ $data->get("description"), $id ] ) != null)
			return null;
		$group->description = $data->get("description");
		$app['redbean']->store($group);
	    return $group;
    }
	
	
}