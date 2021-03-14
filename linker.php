<?php
include('./config/controllers.php');
include('./config/render.php');

$render = new Render();

$auth = new AuthController();



if(isset($_GET) & $_GET !=null){

	$url = explode("/", $_GET['url']);
	$base_view = $url[0];

	// import and use the Render
	$view = $render->view($base_view);
	print($view);

}else{
	
}

if(isset($_POST) and !empty($_POST)){
	// Set the POST to a variable
	$data = $_POST;

	if(isset($data['submit'])){
		$email = $data['email'];
		$password = $data['password'];

		$user = json_decode($auth->Login($email,$password),true);

		if(empty($user)){
			$_POST = null;
			header("Location: index.php");
		}
		$week = new DateTime("+1 week");
		setcookie('_token',$user['token'][0]['token'],$week->getTimestamp(),"/",null,null,false);

		if(isset($_COOKIE)){
			if(isset($_COOKIE['_token'])){
			}else{
				if(isset($_POST)){}else{redirect();}
			}
		}
	}

}

function redirect(){
	$protocol ="HTTP";
	$HTTP_HOST = $_SERVER['HTTP_HOST'];
	$site = "New%20folder/263";
	$link = $protocol."://".$HTTP_HOST."/".$site;
	$_SESSION['msg'] = "Requested Page not found. Reverted to base page...";
	header("Location: $link/$url[0]");
}


?>