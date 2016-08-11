<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class CategoryRepository{
    /**
	 * 
	 */
	public function getCategories(Application $app)
	{
		$categories = $app['redbean']->findAll( 'category');
		return Utility::BeansToArrays($categories);
	}
	
	/**
	 * Return a category specified by ID
	 * @param Application $app Silex application
	 * @param int $id Category ID
	 * return Bean Category
	 */
	
	public function getCategoryByID(Application $app, $id)
	{
		$category = $app['redbean']->load( 'category', $id);
		return $category;
	}
	
	public function createCategory(Application $app, $data)
	{
		//Does the description already exist?
		if ($app['redbean']->findOne( 'category', ' description = ? ', [ $data->get("description") ] ) != null)
			return null;
		$category = $app['redbean']->dispense("category");
		$category->description = $data->get("description");
		$app['redbean']->store($category);
	    return $category;    
    }
    
}