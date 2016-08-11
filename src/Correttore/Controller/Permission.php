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
			[
				'route' => 'tasks',
				'method' => 'POST'
			],
			[
				'route' => 'tasks',
				'method' => 'DELETE'
			],
			[
				'route' => 'info',
				'method' => 'GET'
			],
			[
				'route' => 'logout',
				'method' => 'GET'
			],
			[
				'route' => 'groups',
				'method' => 'GET'
			],
			[
				'route' => 'groups',
				'method' => 'POST'
			],
			[
				'route' => 'groups',
				'method' => 'PUT'
			],
			[
				'route' => 'groups',
				'method' => 'DELETE'
			],
			[
				'route' => 'categories',
				'method' => 'GET'
			],
			[
				'route' => 'categories',
				'method' => 'POST'
			],
		],
		'student' => [
			[
				'route' => 'info',
				'method' => 'GET'
			],
			[
				'route' => 'logout',
				'method' => 'GET'
			],
		]
	];
	
	public static function isGranted($role, $method, $route){
	    if ($role == 'admin')
            return true;
	    $search = [
	            'route' => $route,
				'method' => $method
	        ];
	    return in_array($search, self::$permissions[$role]);
	    //return preg_grep()
	}
	

    public static function publicRoute($route)
    {
    	if ($route == 'public')
            return true;
        return false;
    }
}