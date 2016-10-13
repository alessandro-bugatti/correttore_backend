<?php
include 'lib/RedBeanPHP.php';

R::setup( 'mysql:host=127.0.0.1;dbname=c9',
        'alessandro_bugat', '' );

R::exec('drop table task_test');
R::exec('drop table category');
R::exec('drop table task');

//R::wipe('task');
//R::wipe('category');

$test = R::dispense('test');
$test->description = "Test di prova";
$test->is_on = '1';
$test->creation_date = '2016-04-10';



$task = R::dispense( 'task' );


$task->title = 'Somma due interi';
$task->short_title = 'somma';
$task->is_public = '1';
$task->level = '1';
$task->test_cases = '10';
$task->category_id = '1';
$task->user_id = '1';


$test->sharedTaskList[] = $task;

$id = R::store( $task );

$task = R::dispense( 'task' );


$task->title = 'Stampa due interi';
$task->short_title = 'stampa';
$task->is_public = '1';
$task->level = '1';
$task->test_cases = '10';
$task->category_id = '1';
$task->user_id = '1';

$id = R::store( $task );

$test->sharedTaskList[] = $task;

R::exec('ALTER TABLE task ADD UNIQUE(title)');
R::exec('ALTER TABLE task ADD UNIQUE(short_title)');
 
$id = R::store($test);

$solution = R::dispense('solution');
$solution->score = 8;
$solution->file='somma.cpp';
$solution->submitted='2016-10-04 23:22:00';
$solution->task_id = 1;
$solution->user_id = 1;

$id = R::store($solution);

$category = R::dispense( 'category' );
$category->description = 'Sequenza';
$category->type = 'Programmazione';

$id = R::store( $category );

$category = R::dispense( 'category' );
$category->description = 'Selezione';
$category->type = 'Programmazione';


$id = R::store( $category );

$category = R::dispense( 'category' );
$category->description = 'Input/output';
$category->type = 'Programmazione';


$id = R::store( $category );



