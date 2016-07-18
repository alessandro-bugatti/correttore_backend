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
	
	
}