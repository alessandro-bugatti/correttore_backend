<?php
namespace Correttore\Silex\Provider\RedBean;

use Silex\Application;
use RedBeanPHP\SimpleModel;

abstract class Model extends SimpleModel
{
    /**
     * @var Silex\Application Binded app
     */
    protected $app;
    /**
     * Binds app to the modem
     *
     * @param Silex\Application $app App to bind
     *
     * @return void
     */
    public function bindApp(Application $app)
    {
        $this->app = $app;
    }
}