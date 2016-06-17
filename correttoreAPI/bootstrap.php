<?php

use Correttore\Silex\Provider\RedBean\ServiceProvider as RedBeanServiceProvider;

//Add the JsonResponse from the library
use Symfony\Component\HttpFoundation\JsonResponse;
//Add the Request  and Response from the library
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Correttore\User\UserRepository;


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
    if (($token = $request->headers->get('x-authorization-token')) != null) {
        $users = new UserRepository();
        $app['user'] = $users->getUserByToken($app,$token);
        if ($app['user']==null)
            return new JsonResponse(['error'=>'Token not found'], 403);
        }
    else $app['user'] = null;
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


$app->boot();

$app->mount('/v1', $api);
// This should be the last line
$app->run(); // Start the application, i.e. handle the request
?>
