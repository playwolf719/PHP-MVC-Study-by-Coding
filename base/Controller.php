<?php
/**
* Base Controller
*/
class Controller
{
	public $layout=true;

	function __construct()
    {
    	$this->controller=get_class($this);
    }

	public function beforeAction($action){
		
	}

	public function render($name,array $vars = null){
		$view=new View();
		$view->render($name,$vars,$this->layout);
	}
}