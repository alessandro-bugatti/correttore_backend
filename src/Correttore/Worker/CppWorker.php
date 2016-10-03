<?php

namespace Correttore\Worker;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Worker to evaluate C++ problems solutions
 */


class CppWorker extends Worker{
    
    /**
     * This abstract class is the parent of all the concrete classes, each of them 
     * is able to solve a particular kind of problem (cpp, sql, java,...)
     * @param Application $app Silex application
     */
    public function execute(Application $app){
        
    }
    
}