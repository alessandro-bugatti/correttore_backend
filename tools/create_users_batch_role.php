<?php

$group = readline("Inserisci il nome del gruppo: ");
$n_of_users = readline("Quanti utenti vuoi creare?: ");
$role = readline("Che ruolo vuoi che abbiano? (admin, teacher, student): ");
$roles = ['admin', 'teacher', 'student'];
while (!in_array($role, $roles))
    $role = readline("Inserisci un ruolo tra admin, teacher, student: ");

$command_file = fopen($group . "_creation.php",'w');
$password_file = fopen($group . "_password.txt",'w');
//Create header
fwrite($command_file,"<?php \n\ninclude '../init/conf.php';");
fwrite($password_file,"File delle password");

//Create group

fwrite($command_file,"\n\$groupset = R::dispense( 'groupset' );");
fwrite($command_file,"\n\$groupset->description = '$group';");
fwrite($command_file,"\n\$id = R::store( \$groupset);");


//Create users

for ($i = 1; $i <= $n_of_users; $i++){
    $p = bin2hex(openssl_random_pseudo_bytes(4));
    $user = $group . "_user" . $i;
    fwrite($password_file,"\n" . $user . ";" . $p );
    
    fwrite($command_file,"\n////User: $user");
    fwrite($command_file,"\n\$user = R::dispense( 'user' );");
    fwrite($command_file,"\n\$user->name = '$user';");
    fwrite($command_file,"\n\$user->surname = '$user';");
    fwrite($command_file,"\n\$user->username = '$user';");
    fwrite($command_file,"\n\$user->password = '" . password_hash($p, PASSWORD_DEFAULT) ."';");
    fwrite($command_file,"\n\$user->token = '';");
    fwrite($command_file,"\n\$role  = R::find( 'role', ' description = ? ',[ '$role' ]);");
    fwrite($command_file,"\n\$user->description = \$role;");
    fwrite($command_file,"\n\$user->sharedGroupsetList[] = \$groupset;");
    fwrite($command_file,"\n\$id = R::store( \$user );\n");
}

