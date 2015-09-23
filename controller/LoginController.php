<?php
class LoginController {

	private $loginView;
	private $loginUser;
	private $userAuth;
	private $HTMLview;

	public function __construct(User $user, UserAuthorization $userAuth) {
		$this->loginView = new LoginView();
		$this->loginUser = $user;
		$this->userAuth = $userAuth;
	}

	public function doLogin()
	{
		// Checks if the User did press login.
		if ($this->loginView->didUserPressLogin())
		{
			$inputUsername = $this->loginView->getInputUsername();
			$inputPassword = $this->loginView->getInputPassword();

			// If input-username and input-password is empty.
			if ($this->userAuth->tryLoginInputEmpty($inputUsername) && $this->userAuth->tryLoginInputEmpty($inputPassword))
			{
				$this->HTMLview = $this->loginView->response(false, 'Username is missing', false);
			}

			// If input-password is empty.
			else if ($this->userAuth->tryLoginInputEmpty($inputPassword))
			{
				$this->HTMLview = $this->loginView->response(false, 'Password is missing', true);
			}

			// If input-username is empty.
			else if ($this->userAuth->tryLoginInputEmpty($inputUsername))
			{
				$this->HTMLview = $this->loginView->response(false, 'Username is missing', false);
			}
			else {

				// If the login-information is correct.
				if ($this->userAuth->tryLogin($this->loginUser, $inputUsername, $inputPassword))
				{
					// If the User tries to login while the User is already logged in.
					if ($this->userAuth->isAlreadyLoggedInSession($this->loginUser))
					{
						$this->HTMLview = $this->loginView->response(true, '', false);
					}
					else
					{
						// If user selects "Keep me logged in".
						if ($this->loginView->getSelectKeep())
						{
							$this->loginView->setCookie();
							$this->HTMLview = $this->loginView->response(true, 'Welcome and you will be remembered', false);
						}
						else
						{
							$this->HTMLview = $this->loginView->response(true, 'Welcome', false);
						}

						$this->userAuth->setSession($inputUsername, $inputPassword);
					}
				}

				// If the login-information is wrong.
				else
				{
					$this->HTMLview = $this->loginView->response(false, 'Wrong name or password', true);
				}
			}
		}

		// Checks if the User did press logout.
		else if ($this->loginView->didUserPressLogout())
		{
			if ($this->userAuth->isAlreadyLoggedInSession($this->loginUser))
			{
				$this->userAuth->doLogout();
				$this->HTMLview = $this->loginView->response(false, 'Bye bye!', false);
			}
			else
			{
				$this->HTMLview = $this->loginView->response(false, '', false);
			}
		}

		// If the User did not press anything.
		else 
		{
			if ($this->userAuth->isAlreadyLoggedInSession($this->loginUser))
			{
				$this->HTMLview = $this->loginView->response(true, '', false);
			}
			else if ($this->loginView->doesCookiesExist())
			{
				if ($this->loginView->isAlreadyLoggedInCookie($this->loginUser))
				{
					$this->userAuth->setSession($this->loginUser->getUsername(), $this->loginUser->getPassword());
					$this->HTMLview = $this->loginView->response(true, 'Welcome back with cookie', false);
				}
				else
				{
					$this->loginView->deleteCookie();
					$this->HTMLview = $this->loginView->response(false, 'Wrong information in cookies', false);
				}
			}
			else
			{
				$this->HTMLview = $this->loginView->response(false, '', false);
			}
		}
	}

	// Checks if the User is logged in.
	public function isLoggedIn()
	{
		if ($this->userAuth->isAlreadyLoggedInSession($this->loginUser) || ($this->loginView->doesCookiesExist() && $this->loginView->isAlreadyLoggedInCookie($this->loginUser)))
		{
			return true;
		}
		return false;
	}

	// Contains the HTML-respons from the login/logout.
	public function getHTML()
	{
		return $this->HTMLview;
	}
}