<?php
header('Content-Type: application/json');
require('config/controllers.php');

$auth = new AuthController();

// Blacklist Controller
$blacklist = new Blacklist();

// Client controller
$clients = new ClientAccount();

// http Request Headers
$HEADERS = getallheaders();

# Request path info
$path = $_SERVER['PATH_INFO'];
$url = explode('/', $path);

// Use the headers and check and validate token to make requests
$token_valid = $auth->validate_token($HEADERS['token']);

// POST REQUESTS
if(isset($_POST) & !empty($_POST) & $token_valid==200){

	switch ($url[1]) {
		case 'add_client':
			$result = $clients->AddClient($_POST);
			print($result);
			break;
		case 'debt_deposit':
			$result = $blacklist->PayDebt($_POST);
			print_r($result);
			break;
		default:
			# code...
			break;
	}

}

if(isset($_GET) & $token_valid==200){

	switch ($url[1]) {
		case 'clients':
			// return all clients
			# The method return JSON data
			print_r($clients->getAllClients());
			break;
		case 'client_acc':
			if(isset($url[2])){
				print($blacklist->getBlacklistAccount($url[2]));
			}
			break;
		case 'blacklistClient':
			if(isset($url[2])){
				print_r($clients->BlacklistClient($url[2]));
			}
			break;
		default:
			# code...
			break;
	}

}

$_DELETE = $_SERVER['REQUEST_METHOD'];

if(isset($_DELETE) & $token_valid==200){
	switch ($url[1]) {
		case 'deleteClient':
			$client = $clients->deleteClient($url[2]);
			print_r($client);
			break;
		
		default:
			# code...
			break;
	}
}


?>