<?php

use Correttore\Silex\Provider\RedBean\ServiceProvider as RedBeanServiceProvider;

//Add the JsonResponse from the library
use Symfony\Component\HttpFoundation\JsonResponse;
//Add the Request  and Response from the library
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Correttore\Model\UserRepository;
use Correttore\Controller;

// Create the Silex application
$app = new Silex\Application();

$app->register(new RedBeanServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

require 'conf.php';

// Add the configuration, etc. here
$app['debug'] = true;

//Preprocessing application/json data to insert them in
//the request object

$app->before(function (Request $request, Silex\Application $app) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
    $app['user'] = null;
    
    $len = strpos($request->getRequestURI(),'/', 4); 
    if ($len == false) 
        $len = strlen($request->getRequestURI()) - 4; //Il 4 è per saltare /v1/
    else 
        $len -= 4;
    $route = substr($request->getRequestURI(), 4, $len);
        
    if (!Controller\Permission::publicRoute($route) && $request->getMethod() != 'OPTIONS')
        if (($token = $request->headers->get('x-authorization-token')) != null) {
            $users = new UserRepository();
            $app['user'] = $users->getUserByToken($app,$token);
            $method = $request->getMethod();
            if ($app['user'] == null ||
                    !Controller\Permission::isGranted($app['user']->role->description, $method, $route))
                return new JsonResponse(['error'=>'Unauthorized'], 401);
            }
        else
            return new JsonResponse(['error'=>'Forbidden'], 403);

});

//Enabling CORS

$app->match("{url}", function($url) use ($app){
        return "OK";
    })->assert('url', '.*')->method("OPTIONS"); 

$app->after(function (Request $request, Response $response) {
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, x-authorization-token');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods','POST, GET, PUT, DELETE, OPTIONS');
        });

# ROUTING


$app['auth.api'] = $app->share(function() { return new Controller\Auth(); });
$app['user.api'] = $app->share(function() { return new Controller\UserController(); });
$app['task.api'] = $app->share(function() { return new Controller\TaskController(); });
$app['group.api'] = $app->share(function() { return new Controller\GroupController(); });

$api = $app['controllers_factory'];

//Authentication and authorization routes
$api->post('/public/login', 'auth.api:login')
	->bind('login');
$api->get('/info', 'auth.api:info')
	->bind('info');
$api->get('/logout', 'auth.api:logout')
	->bind('logout');

//Test public
$api->get('/public/hello', function () use ($app) {
    return 'Hello world';
});
	
//Test private
$api->get('/hello', function () use ($app) {
    return 'Private';
});
	

//Teachers
$api->get('/teachers', 'user.api:getTeachers')
	->bind('get_teachers');

$api->post('/teachers', 'user.api:createTeacher')
	->bind('create_teacher');

$api->get('/teachers/{id}', 'user.api:getTeacher')
	->bind('get_teacher');
	
$api->put('/teachers/{id}', 'user.api:updateTeacher')
	->bind('update_teacher');

$api->delete('/teachers/{id}', 'user.api:deleteTeacher')
	->bind('delete_teacher');
	
	
//Tasks

$api->get('/tasks/{id}', 'task.api:getTask')
	->bind('get_task');
$api->get('/tasks', 'task.api:getTasks')
	->bind('get_tasks');

//Groups
$api->get('/groups', 'group.api:getGroups')
	->bind('get_groups');
$api->post('/groups', 'group.api:createGroup')
	->bind('create_group');
$api->put('/groups/{id}', 'group.api:updateGroup')
	->bind('update_group');



$app->boot();

$app->mount('/v' . $app['version'], $api);
// This should be the last line
$app->run(); // Start the application, i.e. handle the request
?>
