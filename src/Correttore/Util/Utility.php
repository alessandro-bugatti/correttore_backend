<?php

namespace Correttore\Util;

use Silex\Application;

class Utility{
    
    public static function BeansToArrays($beans)
    {
        foreach($beans as $bean)
			$items[] = $bean->export();
		return $items;
    }
    
    public static function storeFile(Application $app, $folder, $file, $name, $type = '')
    {
        //Maybe there could be some sort of file type checking..
        $file->move($app['task.path'] . '/' . $folder, $name.'.'.$type);
    }
}