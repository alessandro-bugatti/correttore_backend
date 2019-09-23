<?php

do{
	$password = readline("Inserisci la nuova password: ");
	$repeated_password = readline("Reinserisci la password: ");
}while($password != $repeated_password);
$command_file = fopen("new_admin_password.php",'w');
$password_file = fopen("nuova_password_admin.txt",'w');
//Create header
fwrite($command_file,"<?php \n\ninclude '../init/conf.php';");
fwrite($password_file,"Password di amministratore");
fwrite($password_file,"\n$password\n");


//Create users

    
    fwrite($command_file,"\n////User: admin");
    fwrite($command_file,"\n\$user = R::findOne( 'user','username=?',['admin'] );");
    fwrite($command_file,"\n\$user->password = '" . password_hash($password, PASSWORD_DEFAULT) ."';");
    fwrite($command_file,"\n\$id = R::store( \$user );\n");


