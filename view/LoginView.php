<?php

namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $isLoggedIn = false;
	private $responseView;

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($loggedInStatus, $statusMessage, $rememberInputUsername) {

		if ($loggedInStatus)
		{
			$response = $this->generateLogoutButtonHTML($statusMessage);
		}
		else 
		{
			if ($rememberInputUsername)
			{
				$response = $this->generateLoginFormHTML($statusMessage, $this->getInputUsername());
			}
			else {
				$response = $this->generateLoginFormHTML($statusMessage, '');
			}
		}
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message, $previousInputUsername) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $previousInputUsername . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
	// Tells if the User is logged in.
	public function getIsLoggedIn()
	{
		return $this->isLoggedIn;
	}

	// Asks if the User did press the login-button.
	public function didUserPressLogin()
	{
		return isset($_POST[self::$login]);
	}

	// Asks if the User did press the logout-button.
	public function didUserPressLogout()
	{
		return isset($_POST[self::$logout]);
	}

	// Gets the Name that the User inputs.
	public function getInputUsername()
	{
		return $_POST[self::$name];
	}

	// Gets the Password that the User inputs.
	public function getInputPassword()
	{
		return $_POST[self::$password];
	}

	// Gets if the User did select "Keep me logged in".
	public function getSelectKeep()
	{
		return isset($_POST[self::$keep]);
	}

	// Sets two cookies if the User selects "Keep me logged in".
	public function setCookie()
	{
		$cookieUsername = $this->getInputUsername();
		$cookieHashedPassword = hash('ripemd160', $this->getInputPassword());
		setcookie(self::$cookieName, $cookieUsername, time() + (86400 * 30), "/");
		setcookie(self::$cookiePassword, $cookieHashedPassword, time() + (86400 * 30), "/");
	}

	// Delete cookies if the cookies has wrong logininformation.
	public function deleteCookie()
	{
		unset($_COOKIE[self::$cookieName]);
		unset($_COOKIE[self::$cookiePassword]);
		setcookie(self::$cookieName, "", time() - 3600);
		setcookie(self::$cookiePassword, "", time() - 3600);
	}
	
	// Checks if the cookies contains the correct information.
	public function isAlreadyLoggedInCookie(\model\User $user)
	{
		$cookie_username = $_COOKIE[self::$cookieName];
		$cookie_password = $_COOKIE[self::$cookiePassword];
		$hashedPassword = hash('ripemd160', $user->getPassword());
		if ($user->getUsername() == $cookie_username && $cookie_password == $hashedPassword)
		{
			return true;
		}
		return false;
	}

	// Checks if there is already cookies with logininformation.
	public function doesCookiesExist()
	{
		if(isSet($_COOKIE[self::$cookieName]) && isSet($_COOKIE[self::$cookiePassword]))
		{
			return true;
		}
		return false;
	}
}