<?php

namespace model;

class User {
	private $username;
	private $password;

	public function __construct($myUsername, $myPassword)
	{
		$this->username = $myUsername;
		$this->password = $myPassword;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getPassword()
	{
		return $this->password;
	}
}