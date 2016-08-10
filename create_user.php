<?php
include 'lib/RedBeanPHP.php';

R::setup( 'mysql:host=127.0.0.1;dbname=c9',
        'alessandro_bugat', '' );

R::exec('drop table groupset_user');
R::exec('drop table groupset');
R::exec('drop table task');
R::wipe('user');
R::wipe('role');


$user = R::dispense( 'user' );
$user->name = 'Alessandro';
$user->surname = 'Bugatti';
$user->username = 'alex';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da';
$user->role_id = '1';


$groupset= R::dispense( 'groupset' );
$groupset->description = 'classe 3AI 2015-2016';
$user->ownGroupsetList[] = $groupset;
$id = R::store( $groupset);
$id = R::store( $user );

$groupset2= R::dispense( 'groupset' );
$groupset2->description = 'classe 4AI 2015-2016';
$user->ownGroupset2List[] = $groupset2;
$id = R::store( $groupset2);
$id = R::store( $user );



$user = R::dispense( 'user' );
$user->name = 'Alekos';
$user->surname = 'Filini';
$user->username = 'alekos';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '';
$user->role_id = '2';

$user->sharedGroupsetList[] = $groupset;
$user->sharedGroupsetList[] = $groupset2;
$id = R::store( $user );



$user = R::dispense( 'user' );
$user->name = 'Al';
$user->surname = 'Gollini';
$user->username = 'algol';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '';
$user->role_id = '2';

$user->sharedGroupsetList[] = $groupset;
$id = R::store( $user );



$user = R::dispense( 'user' );
$user->name = 'Alessandro';
$user->surname = 'Bugatti';
$user->username = 'admin';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '67c9612e6218fa0c7ec3675583dbeb26572dd8a583d1561723987190ecce0043';
$user->role_id = '3';
$id = R::store( $user );

$user = R::dispense( 'user' );
$user->name = 'Alberto';
$user->surname = 'Regosini';
$user->username = 'alby';
$user->password = '$2y$10$d/doEy2cRSCfIaIaoQ4QAOWdT13SzvXdVZW1M4xQxHwa.Xpk5ZygS'; //pippo
$user->token = '059bc89a05d7590fbdda8c04503c70a7966248c4a4b6c4a3204d51209812d5a2';
$user->role_id = '1';
$id = R::store( $user );

$groupset= R::dispense( 'groupset' );
$groupset->description = 'classe 4BI 2015-2016';
$user->ownGroupsetList[] = $groupset;
$id = R::store( $groupset);
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

