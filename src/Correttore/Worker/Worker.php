<?php

namespace Correttore\Worker;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Factory to create different kind of worker. Now is only a kind of a stub
 */


abstract class Worker{
    
    /**
     * This abstract class is the parent of all the concrete classes, each of them 
     * is able to solve a particular kind of problem (cpp, sql, java,...)
     * @param Application $app Silex application
     */
    abstract public function execute(Application $app);
    
}