<?php

namespace Correttore\Worker;

use Silex\Application;

/**
 * This abstract class is the parent of all the concrete classes, each of them 
 * is able to solve a particular kind of problem (cpp, sql, java,...)
 */


abstract class Worker{
    protected $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    /**
     * Solve the problem
     */
    abstract public function execute($file, $id);
}