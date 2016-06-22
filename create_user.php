<?php
include 'lib/RedBeanPHP.php';

R::setup( 'mysql:host=127.0.0.1;dbname=c9',
        'alessandro_bugat', '' );

R::wipe('user');
R::wipe('role');

$user = R::dispense( 'user' );
$user->name = 'Alessandro';
$user->surname = 'Bugatti';
$user->username = 'alex';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '';
$user->role_id = '1';
$id = R::store( $user );

$user = R::dispense( 'user' );
$user->name = 'Alekos';
$user->surname = 'Filini';
$user->username = 'alekos';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '';
$user->role_id = '2';
$id = R::store( $user );

$user = R::dispense( 'user' );
$user->name = 'Alessandro';
$user->surname = 'Bugatti';
$user->username = 'admin';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '';
$user->role_id = '3';
$id = R::store( $user );


$role = R::dispense( 'role' );
$role->description = 'teacher';
$id = R::store( $role );

$role = R::dispense( 'role' );
$role->description = 'student';
$id = R::store( $role );

$role = R::dispense( 'role' );
$role->description = 'admin';
$id = R::store( $role );

