<?php
class UserAuthorization {

	public function tryLogin(User $user, $inputUsername, $inputPassword)
	{
		if ($inputUsername == $user->getUsername() && $inputPassword == $user->getPassword())
		{
			return true;
		}
		return false;
	}

	public function setSession($inputUsername, $inputPassword)
	{
		$_SESSION["username"] = $inputUsername;
		$_SESSION["password"] = $inputPassword;
	}

	public function tryLoginNoInput($inputUsername, $inputPassword)
	{
		if ($inputUsername == '' && $inputPassword == '')
		{
			return true;
		}
		return false;
	}

	public function tryLoginInputEmpty($input)
	{
		if ($input == '')
		{
			return true;
		}
		return false;
	}

	public function doLogout()
	{
		session_unset();
	}

	public function isAlreadyLoggedInSession(User $user)
	{
		if (isset($_SESSION["username"]) && isset($_SESSION["password"])) 
		{
			if ($_SESSION["username"] == $user->getUsername() && $_SESSION["password"] == $user->getPassword())
			{
				return true;
			}
			return false;
		}
		return false;
	}
}