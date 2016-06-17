<?php

namespace Correttore\User;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class UserRepository{
    /**
	 * 
	 */
	public function getUserByUsername(Application $app, $username)
	{
		$param[] = $username;
		$user = $app['redbean']->findOne( 'user', ' username = ?', $param);
		return $user;
	}
	
	public function getAuthenticatedUser(Application $app, $username, $password)
	{
		$user = $this->getUserByUsername($app, $username);
		if ($user != null && password_verify($password,$user->password)){
			$token   = bin2hex(openssl_random_pseudo_bytes(32));
			$user->token = $token;
			$app['redbean']->store($user);
			return $user;
		}
		else
			return null;
	}
	
	public function getUserByToken(Application $app, $token)
	{
		$param[] = $token;
		$user = $app['redbean']->findOne( 'user', ' token = ?', $param);
		return $user;
	}
	
	public function clearTokenByUsername(Application $app, $username)
	{
		$user = $this->getUserByUsername($app, $username);
		$user->token = '';
		$app['redbean']->store($user);
	}
}