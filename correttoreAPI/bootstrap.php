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
        if (($token = $request->headers->get('X-Authorization-Token')) != null) {
            $users = new UserRepository();
            $app['user'] = $users->getUserByToken($app,$token);
            $method = $request->getMethod();
            if ($app['user'] == null ||
                    !Controller\Permission::isGranted($app['user']->role->description, $method, $route))
                {
                    return new JsonResponse(['error'=>'Unauthorized'], 401);
                }
            }
        else
            return new JsonResponse(['error'=>'Forbidden'], 403);
    if (($token = $request->headers->get('X-Authorization-Token')) != null) {
            $users = new UserRepository();
            $app['user'] = $users->getUserByToken($app,$token);
    }    

});

//Enabling CORS

$app->match("{url}", function($url) use ($app){
        return "OK";
    })->assert('url', '.*')->method("OPTIONS"); 

$app->after(function (Request $request, Response $response) {
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Content-Length, x-authorization-token');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods','POST, GET, PUT, DELETE, OPTIONS');
        });

//Exception handling
$app->error(function (\Exception $e, $code) {
    return new Response($e->getMessage());
});

# ROUTING


$app['auth.api'] = $app->share(function() { return new Controller\Auth(); });
$app['user.api'] = $app->share(function() { return new Controller\UserController(); });
$app['task.api'] = $app->share(function() { return new Controller\TaskController(); });
$app['group.api'] = $app->share(function() { return new Controller\GroupController(); });
$app['category.api'] = $app->share(function() { return new Controller\CategoryController(); });
$app['problem.api'] = $app->share(function() { return new Controller\ProblemController(); });
$app['submission.api'] = $app->share(function() { return new Controller\SubmissionController(); });
$app['test.api'] = $app->share(function() { return new Controller\TestController(); });

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
	->bind('student');

$api->get('/teachers/{id}', 'user.api:getTeacher')
	->bind('studenteacher');
	
$api->put('/teachers/{id}', 'user.api:updateTeacher')
	->bind('studentte_teacher');

$api->delete('/teachers/{id}', 'user.api:deleteTeacher')
	->bind('studentte_teacher');
	
//Students
$api->get('/students', 'user.api:getStudents')
	->bind('get_students');

$api->post('/students', 'user.api:createStudent')
	->bind('create_student');

$api->get('/students/{id}', 'user.api:getStudent')
	->bind('get_student');
	
$api->put('/students/{id}', 'user.api:updateStudent')
	->bind('update_student');

$api->delete('/students/{id}', 'user.api:deleteStudent')
	->bind('delete_student');
	
//Tasks
$api->get('/tasks/{id}', 'task.api:getTask')
	->bind('get_task');
$api->get('/tasks', 'task.api:getTasks')
	->bind('get_tasks');
$api->post('/tasks', 'task.api:createTask')
	->bind('create_task');
$api->post('/tasks/{id}', 'task.api:updateTask')
	->bind('update_task');
$api->delete('/tasks/{id}', 'task.api:deleteTask')
	->bind('delete_task');



//Groups
$api->get('/groups', 'group.api:getGroups')
	->bind('get_groups');
$api->post('/groups', 'group.api:createGroup')
	->bind('create_group');
$api->put('/groups/{id}', 'group.api:updateGroup')
	->bind('update_group');
$api->delete('/groups/{id}', 'group.api:deleteGroup')
	->bind('delete_group');
$api->put('/groups/{group_id}/student/{user_id}', 'group.api:addUserToGroup')
	->bind('add_user_to_group');
$api->delete('/groups/{group_id}/student/{user_id}', 'group.api:removeUserFromGroup')
	->bind('remove_user_from_group');

//Categories
$api->get('/public/categories', 'category.api:getCategories')
	->bind('get_categories');
$api->get('/categories/{id}', 'category.api:getCategory')
	->bind('get_category');
$api->post('/categories', 'category.api:createCategory')
	->bind('create_category');

//Problems: a problem is a task from the point of view of the student
$api->get('/public/problems', 'problem.api:getPublicProblems')
	->bind('get_public_problems');
$api->get('/public/problems/{id}.pdf', 'problem.api:getPublicProblemPDF')
	->bind('get_public_problem_pdf');
$api->get('/public/problems/{id}', 'problem.api:getPublicProblem')
	->bind('get_public_problem');
$api->get('/problems/{id}.pdf', 'problem.api:getProblemPDF')
	->bind('get_problem_pdf');
$api->get('/problems/{id}', 'problem.api:getTestProblem')
	->bind('get_test_problem');


//Submission
$api->post('/public/submissions/{id}', 'submission.api:postPublicSubmission')
	->bind('post_public_submission');
$api->post('/public/submission/{id}', 'submission.api:postPublicSubmission')
	->bind('post_public_submission');
$api->post('/submissions/tests/{test_id}/tasks/{task_id}', 'submission.api:postTestSubmission')
	->bind('post_test_submission');



//Test
$api->get('/tests', 'test.api:getTests')
	->bind('get_tests');
$api->get('/tests/{id}/tasks', 'task.api:getTasksByTestId')
	->bind('get_tasks_by_test_id');
$api->post('/tests', 'test.api:createTest')
	->bind('create_test');
$api->put('/tests/{id}', 'test.api:updateTest')
	->bind('update_test');
$api->delete('/tests/{id}', 'test.api:deleteTest')
	->bind('delete_test');
$api->put('/tests/{test_id}/task/{task_id}', 'test.api:addTaskToTest')
	->bind('add_task_to_test');
$api->delete('/tests/{test_id}/task/{task_id}', 'test.api:removeTaskFromTest')
	->bind('remove_task_from_test');
$api->get('/tests/{test_id}/results', 'test.api:getTestResults')
	->bind('get_test_results');
$api->get('/tests/{test_id}/users/{user_id}/details', 'test.api:getTestResultsByUser')
	->bind('get_test_results_by_user');


$app->boot();

$app->mount('/v' . $app['version'], $api);
// This should be the last line

$app->run(); // Start the application, i.e. handle the request
?>
