<?php

namespace Correttore\Util;

use Silex\Application;

class Utility{
    
    public static function BeansToArrays($beans)
    {
        foreach($beans as $bean)
			$items[] = $bean->export();
		if ($items == null || count($items) == 0)
		    return array();
		return $items;
    }
    /**
     * Remove some fields from an array
     * @param array &$array The array that has fields to be removed
     * @param array $toRemove The list of fields to be removed
     */
    public static function RemoveFieldsFromArray(&$array, $toRemove)
    {
        foreach ($toRemove as $field)
                unset($array[$field]);
    }
    /**
     * Remove some fields from an array of arrays
     * @param array &$array The array containing arrays that have fields to be removed
     * @param array $toRemove The list of fields to be removed
     */
    public static function RemoveFieldsFromArrays(&$array, $toRemove)
    {
        if ($array == null || count($array) == 0)
            return;
            
        foreach ($array as &$row)
            self::RemoveFieldsFromArray($row, $toRemove);
    }
    
    public static function storeTaskFile(Application $app, $folder, $file, $name, $type = '')
    {
        //Maybe there could be some sort of file type checking..
        $file->move($app['task.path'] . '/' . $folder, $name.'.'.$type);
    }
    
    public static function storeSubmittedFile(Application $app, $folder, $file, $name, $type = '')
    {
        //Maybe there could be some sort of file type checking..
        //var_dump($app['user.path'] . $folder . '/' . $name . '.'.$type);
        //var_dump($file);
	rename($file['tmp_name'], $app['user.path'] . $folder .'/' . $name . '.' . $type);
	//$file->move($app['user.path']  . $folder, $name.'.'.$type);
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
    
    public static function rmEvaluationFolder($folder)
    {
        self::removeDir($folder);
    }
    
    public static function createEvaluationRandomFolder(Application $app)
    {
        $test_dir = $app['evaluation.dir'] . "/" . md5(microtime() . mt_rand());
        if (!mkdir($test_dir,0777,true))
            return null;
        else {
            return $test_dir;
        }
    }
}
