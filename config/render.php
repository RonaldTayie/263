<?php

class Render{

	function __construct (){
	}

	public function view($viewname){
		include('Settings.php');
		
		if(!in_array(strtolower($viewname), $Views)){
			return 'views/index.php';
		}

		$dir = $Views['dir'];
		$ext = $Views['ext'];

		$view = $dir.'/'.$Views[$viewname].$ext;

		return $view;

	}

}

?>