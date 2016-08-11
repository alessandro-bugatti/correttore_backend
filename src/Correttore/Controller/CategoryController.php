<?php

namespace Correttore\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Model\CategoryRepository;

class CategoryController{
    
    /**
     * Return all problem's categories
     * @param Application $app Silex application
     * @return JsonResponse The JSON response
     * @todo Now everyone can get ths list, maybe could be modified
     */
    public function getCategories(Application $app)
    {
        $categoryRep = new CategoryRepository();
        $categories = $categoryRep->getCategories($app);
        return new JsonResponse($categories,200);
    }
    
    /**
     * Return one category specified by ID
     * @param Application $app Silex application
     * @param int $id Category id
     * @return JsonResponse The JSON response
     * @todo Now everyone can get the category, maybe could be modified
     */
    public function getCategory (Application $app, $id)  {
        $categorys = new CategoryRepository();
        $category = $categorys->getCategoryByID($app, $id);
        if ($category->ID == 0)
            return new Response('', 404);
        return new JsonResponse($category->export(), 200);
    }
    
    /**
     * Create a new category
     * @param Application $app Silex application
     * @param array $request Data sent from client
     * @return JsonResponse The JSON response with category data
     * 
     * {@internal ATTENTION: Only teachers can create new category, is it right? }
     */
    public function createCategory(Application $app, Request $request)  {
        $categoryRep = new CategoryRepository();
        if ($app['user']->role->description == 'teacher'){
            $category = $categoryRep->createCategory($app, $request->request);
            if ($category == null)
                return new JsonResponse(['error'=>'category already exist'], 409);
            return new JsonResponse($category->export(),200);
        }
        else
            return new JsonResponse('',401);
    }
    
}