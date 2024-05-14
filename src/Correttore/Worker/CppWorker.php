<?php

namespace Correttore\Worker;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Correttore\Util\Utility;
use Correttore\Model\TaskRepository;

/**
 * Worker to evaluate C++ problems solutions
 */


class CppWorker extends Worker{
    
    public function __construct(Application $app){
        parent::__construct($app);
    }
    
    private function compile($test_dir)
    {
        //Compilation
        ob_start();
        system("g++ -std=c++11 $test_dir/main.cpp -o $test_dir/main 2>&1");
        return ob_get_clean();
    }
    
    private function testCaseCopy($test_dir, $task)
    {
        $source = $this->app['task.path'] . $task->short_title . "/solution.zip";
        $dest = $test_dir . "/solution.zip";
        copy($source , $dest );
        ob_start();
        system("unzip $dest -d $test_dir");
        ob_get_clean();
        //copy("driver", $dir . "/driver");
        //chmod($dir . "/driver",755);
    }
    
    private function doTest($id, $task, $test_dir)
    {
        $command = $this->app['cppdriver'] .
            " -n " . $task->test_cases . 
            " -i $test_dir/input%d.txt -o $test_dir/output%d.txt -t 2 -s $test_dir/main 2>&1";
        ob_start();
        system($command);
        $output = ob_get_clean();
        $arr = explode("\n", $output);
        //Looking for Score
    	foreach($arr as $key=>$item)
    	{
    		if (strpos($item,"Score") === 0)
    		{
    			$p = explode(" ", $item);
    			$score_key = $key;
    		}
    	}
    	unset($arr[$score_key]); //remove score
    	$result['lines'] = array_filter($arr);
    	$result['score'] = $p[1];
    	return $result;
    }

    public function execute($file, $id){
    
    $repository = new TaskRepository();
    $task = $repository->getTaskByID($this->app,$id);
    
    //Evaluation folder creation
    $test_dir = Utility::createEvaluationRandomFolder($this->app);
    if ($test_dir == null)
        throw new \Exception("Unable to create evaluation folder");
    //Source file copy
	//var_dump($file);
    if (copy($file['tmp_name'], $test_dir ."/main.cpp" )==false)
    {
        Utility::rmEvaluationFolder($test_dir);
        throw new \Exception("Unable to cop0y solution in evaluation folder");
    }
        
    //Compilation
    $result = $this->compile($test_dir);
    
    if ($result != "")
    {
        Utility::rmEvaluationFolder($test_dir);    
        throw new \Exception("Compilation errors or warnings: \n" . $result);
    }
        
    //Test case copy
    
    $this->testCaseCopy($test_dir, $task);
    //Execute tests
    $result = $this->doTest($id, $task, $test_dir);
    Utility::rmEvaluationFolder($test_dir);
    return $result;
    }
    
    
    
    
}
