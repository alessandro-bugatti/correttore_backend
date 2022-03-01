<?php

$group = readline("Inserisci il nome del gruppo (deve essere uguale al nome del file): ");

$command_file = fopen($group . "_creation.php",'w');
$password_file = fopen($group . "_password.txt",'w');
//Create header
fwrite($command_file,"<?php \n\ninclude '../init/conf.php';");
fwrite($password_file,"File delle password");

//Retrieve group

fwrite($command_file,"\n\$groupset = R::findOne( 'groupset', ' description = ? ', [\"$group\"]);");

//Create users
$users = file($group . '.txt');
if ($users == FALSE){
    echo 'File degli utenti non trovato';
    exit(1);
}
foreach($users as $user)
{
    $user = rtrim($user);
    if (strlen($user) == 0) continue;
    $names = explode('.',$user);
    $p = bin2hex(openssl_random_pseudo_bytes(4));
    fwrite($password_file,"\n" . $user . ";" . $p );

    fwrite($command_file,"\n////User: $user");
    fwrite($command_file,"\n\$user = R::dispense( 'user' );");
    fwrite($command_file,"\n\$user->name = '{$names[1]}';");
    fwrite($command_file,"\n\$user->surname = '{$names[0]}';");
    fwrite($command_file,"\n\$user->username = '$user';");
    fwrite($command_file,"\n\$user->password = '" . password_hash($p, PASSWORD_DEFAULT) ."';");
    fwrite($command_file,"\n\$user->token = '';");
    fwrite($command_file,"\n\$user->role_id = '2';");
    fwrite($command_file,"\n\$user->sharedGroupsetList[] = \$groupset;");
    fwrite($command_file,"\n\$id = R::store( \$user );\n");
}
