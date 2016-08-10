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
    
    /**
     * Remove a file or a directory even if it contains files or subdir
     */
    private static function removeDir($target) {
        if(is_dir($target)){
            $files = glob( $target . '/*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file )
                self::removeDir( $file );      
            rmdir( $target );
        } 
        elseif (is_file($target)) {
            unlink( $target );  
        }
    }
    
    public static function rmTaskFolder(Application $app, $folder)
    {
        self::removeDir($app['task.path'] . $folder);
    }
}