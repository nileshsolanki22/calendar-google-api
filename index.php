<?php
session_start();
//require_once 'dbcontroller.php';

//Google API PHP Library includes
require __DIR__ . '/vendor/autoload.php';
//include_once("account.php");

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
 $client_id = '';
 $client_secret = '';
 $redirect_uri = 'http://localhost/calendar/';
 $simple_api_key = '';
 
 
//Create Client Request to access Google API
$client = new Google_Client();
$client->setApplicationName("");
    $client->setAuthConfigFile(CLIENT_SECRET_PATH);
$client->setAccessType('offline');
$client->addScope("https://www.googleapis.com/auth/userinfo.email"." https://www.googleapis.com/auth/calendar"." https://www.googleapis.com/auth/calendar.readonly");

//Send Client Request
$objOAuthService = new Google_Service_Oauth2($client);

//calendar service
$service = new Google_Service_Calendar($client);

//Logout
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
  $client->revokeToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
  $userData = $objOAuthService->userinfo->get();
  echo "logged in ";
  /*
  if(!empty($userData)) {
	$objDBController = new DBController();
	$existing_member = $objDBController->getUserByOAuthId($userData->id);
	if(empty($existing_member)) {
		$objDBController->insertOAuthUser($userData);
	}
  }
  */
  $_SESSION['access_token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
require_once("loginpageview.php")
?>