<?php

namespace Correttore\Model;

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
	
	public function getUserByID(Application $app, $id)
	{
		$user = $app['redbean']->load( 'user', $id);
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
	
	public function getUsersByRole(Application $app, $role)
	{
		$users = $app['redbean']->getAll( 'SELECT user.id as id, name, surname, username 
			FROM user LEFT JOIN role ON user.role_id = role.id 
			WHERE role.description = :role',
	        [':role' => $role]
    	);
		return $users;
	}
	
	public function createUser(Application $app, $data)
	{
		//Does the username already exist?
		if ($app['redbean']->findOne( 'user', ' username = ? ', [ $data->get("username") ] ) != null)
			return null;
		$user = $app['redbean']->dispense("user");
		$role = $app['redbean']->findOne( 'role', ' description = ? ', [ $data->get("role") ] );
    	$user->name = $data->get("name");
    	$user->surname = $data->get("surname");
    	$user->username = $data->get("username");
    	$user->password = password_hash($data->get("username"),PASSWORD_DEFAULT);
    	$user->role = $role; 
    	$app['redbean']->begin();
	    $app['redbean']->store($user);
	    return $user;    
    }
    
    public function updateUser(Application $app, $data, $id)
	{
		//Does the user exist?
		if (($user = $app['redbean']->load( 'user', $id )) == null)
			return null;
		//Does the username already exist in another record?
		if ($app['redbean']->findOne( 'user', ' username = ? and ID <> ?', [ $data->get("username"), $id ] ) != null)
			return null;
		$role = $app['redbean']->findOne( 'role', ' description = ? ', [ $data->get("role") ] );
    	$user->name = $data->get("name");
    	$user->surname = $data->get("surname");
    	$user->username = $data->get("username");
    	$user->password = password_hash($data->get("username"),PASSWORD_DEFAULT);
    	$user->role = $role; 
    	//$app['redbean']->begin();
		$app['redbean']->store($user);
	    return $user;    
    }
    
    public function deleteUser(Application $app, $id)
	{
		//Does the user exist?
		if (($user = $app['redbean']->load( 'user', $id )) == null)
			return false;
		$app['redbean']->trash($user);
	    return true;    
    }
    
    /**
     * Ckeck if a user is a student or not
     * @param Application $app Silex application
     * @param int $id User id
     * @return bool True if the user is a student, false otherwise
     */
    public function isStudent(Application $app, $id){
    	if (($user = $app['redbean']->load( 'user', $id )) == null)
			return false;
		if ($user->role->description != 'student')
			return false;
		return true;
    }
    	
}