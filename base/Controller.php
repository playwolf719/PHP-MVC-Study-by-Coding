<?php
/**
* base class
*/
class Controller
{
	public $layout=true;
	
	function __construct()
	{
		# code...
	}

	public function output(){
		if($layout)include_once("views/header.php");
		if($layout)include_once("views/footer.php");
	}
}