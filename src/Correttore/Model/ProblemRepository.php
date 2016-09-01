<?php

namespace Correttore\Model;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Correttore\Util\Utility;

class ProblemRepository{
    /**
     * Get the list of public problem
     * @param Application $app Silex application
     * @return Array The list of IDs and title of public problems
     */
	public function getPublicProblems(Application $app)
	{
		$publicProblems = $app['redbean']->getAll( 'SELECT id, title FROM task WHERE is_public = "1"' );
		return $publicProblems;
	}
	
}