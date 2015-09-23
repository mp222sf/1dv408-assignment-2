<?php



// Include views
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

// Include controllers
require_once('controller/LoginController.php');

// Include models
require_once('model/User.php');
require_once('model/UserAuthorization.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();
$loginUser = new \model\User("Admin", "Password");
$userAuth = new \model\UserAuthorization();
$login = new \controller\LoginController($loginUser, $userAuth);

$login->doLogin(); 

$htmlReponse = $login->getHTML();
$htmlDateTime = $dtv->show();
$loggedInStatus = $login->isLoggedIn();
$lv->render($loggedInStatus, $htmlReponse, $htmlDateTime);

