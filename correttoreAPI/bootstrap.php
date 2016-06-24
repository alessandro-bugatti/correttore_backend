<?php

use Correttore\Silex\Provider\RedBean\ServiceProvider as RedBeanServiceProvider;

//Add the JsonResponse from the library
use Symfony\Component\HttpFoundation\JsonResponse;
//Add the Request  and Response from the library
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Correttore\User\UserRepository;
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
    $route = substr($request->getRequestURI(), 3, strpos($request->getRequestURI(),'/', 4) - 3 ); //Il 4 è per saltare /v1/
    if (!Controller\Permission::publicRoute($route))
        if (($token = $request->headers->get('x-authorization-token')) != null) {
            $users = new UserRepository();
            $app['user'] = $users->getUserByToken($app,$token);
            $route = substr(strrchr($request->getURI(),'/'),1);
            $method = $request->getMethod();
            if ($app['user'] == null ||
                    !Controller\Permission::isGranted($app['user']->role->description, $method, $route))
                return new JsonResponse(['error'=>'Forbidden'], 403);
            }
        else
            return new JsonResponse(['error'=>'Forbidden'], 403);

});

//Enabling CORS
$app->after(function (Request $request, Response $response) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        });

# ROUTING
use Correttore\Controller;

$app['auth.api'] = $app->share(function() { return new Controller\Auth(); });

$api = $app['controllers_factory'];

$api->post('/login', 'auth.api:login')
	->bind('login');
$api->get('/info', 'auth.api:info')
	->bind('info');
$api->get('/logout', 'auth.api:logout')
	->bind('logout');

//Teachers
$api->get('/teachers', 'user.api:getTeachers')
	->bind('get_teachers');

$app->boot();

$app->mount('/v' . $app['version'], $api);
// This should be the last line
$app->run(); // Start the application, i.e. handle the request
?>
