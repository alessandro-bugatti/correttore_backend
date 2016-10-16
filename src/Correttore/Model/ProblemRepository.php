<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class ProblemRepository{
    /**
     * Get the list of public problems
     * @param Application $app Silex application
     * @return Array The list of IDs and title of public problems
     */
	public function getPublicProblems(Application $app)
	{
		$publicProblems = $app['redbean']->getAll( 'SELECT id, title FROM task WHERE is_public = "1"' );
		return $publicProblems;
	}
	
	/**
     * Get a problem identified by id
     * @param Application $app Silex application
     * @param int $id Problem id
     * @return Array The desired problem in form of array, not bean
     */
	public function getPublicProblemByID(Application $app, $id)
	{
		$problem = $app['redbean']->getRow( 'SELECT id, title, short_title, level FROM task ' .
					'WHERE id = :id AND is_public = 1',
        	[':id' => $id]);
		return $problem;
	}
	
}