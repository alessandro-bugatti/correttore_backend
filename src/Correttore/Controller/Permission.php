<?php

/**
 * Simple class to manage permissions
 * Now the permissions are hardcoded inside
 * permissions array
 */
 
namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class Permission{

    private static $permissions = [
	    'teacher' => [
			[
				'route' => 'tasks',
				'method' => 'GET'
			],
		]
	];
	
	public static function isGranted($role, $method, $route){
	    if ($role == 'admin')
            return true;
	    //echo $route . " " . $method;
	    $search = [
	            'route' => $route,
				'method' => $method
	        ];
	    return in_array($search, self::$permissions[$role]);
	}
	

    public static function publicRoute($route)
    {
        if ($route == '/public')
            return true;
        return false;
    }
}