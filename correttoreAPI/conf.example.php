<?php
// Add the configuration, etc. here

//Enable or disable debug
$app['debug'] = true;

//Database configuration for RedBean
$app['redbean.database'] = 'mysql:host=127.0.0.1;dbname=correttore';
$app['redbean.username'] = 'correttore';
$app['redbean.password'] = 'correttore';

//Enable the application to be installed in a subfolder
//inside Apache root
$app['subdir'] = 'correttoreapi';

//API version
$app['version'] = 1;

//Folder which contains the tasks
$app['task.path'] = '../tasks/';

//Folder which contains the users' folders
$app['user.path'] = '../users/';

//C++ driver executable
$app['cppdriver'] = '../bin/driver';

//Folder to store some kind of problem during evaluation phase
$app['evaluation.dir'] = '../temp';

//Folder to store temporary data
$app['temporary.dir'] = '../temp';
