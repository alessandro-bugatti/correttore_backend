<?php
// Add the configuration, etc. here

//Enable or disable debug
$app['debug'] = true;

//Database configuration for RedBean
$app['redbean.database'] = 'mysql:host=127.0.0.1;dbname=c9';
$app['redbean.username'] = 'alessandro_bugat';
$app['redbean.password'] = '';

//API version
$app['version'] = 1;

//Folder which contains the tasks
$app['task.path'] = '../tasks/';

//Folder which contains the users' folders
$app['user.path'] = '../users/';

//C++ driver executable
$app['cppdriver'] = '../driver';

//Folder to store some kind of problem during evaluation phase
$app['evaluation.dir'] = '../temp';