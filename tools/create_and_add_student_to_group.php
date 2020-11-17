<?php

include '../init/conf.php';

$nome = readline("Inserisci il nome dell'utente: ");
$cognome = readline("Inserisci il cognome dell'utente: ");
$group = readline("Inserisci il nome del gruppo (deve essere un gruppo esistente): ");

$groupset = R::findOne( 'groupset', ' description = :description ', 
	['description' => $group] );

$nome = strtolower($nome);
$cognome = strtolower($cognome);

$p = bin2hex(openssl_random_pseudo_bytes(4));
$user = R::dispense( 'user' );
$user->name = $nome;
$user->surname = $cognome;
$user->username = $cognome . "." . $nome;
$user->password = password_hash($p, PASSWORD_DEFAULT);
$user->token = '';
$user->role_id = '2';
$user->sharedGroupsetList[] = $groupset;
$id = R::store($user);

echo 'Creato ' . $user->username . ' con password ' . $p ."\n";
