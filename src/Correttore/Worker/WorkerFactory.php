<?php

namespace Correttore\Worker;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Factory to create different kind of worker. Now is only a kind of a stub
 */


class WorkerFactory{
    
    /**
     * Create a new worker based on type parameter
     * @param Application $app Silex application
     * @param string $type The string to identity the kind of worker
     * @return Worker A concrete worker
     * @todo It is not clear if the string type is the right way to do this thing, to be investigated 
     */
    public static function createWorker(Application $app, $type)
    {
        if ($type == "cpp")
            return new CppWorker();
    }
    
}