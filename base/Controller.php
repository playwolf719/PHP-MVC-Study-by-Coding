<?php
/**
* Base Controller
*/
class Controller
{
	public $layout=true;
	public $header="header.php";
	public $footer="footer.php";


	function __construct()
    {
    	$this->controller=get_class($this);
    }

	public function beforeAction($action){
		
	}

	public function render($name,array $vars = null){
		if($this->layout)include_once(SITE_PATH."view/layout/".$this->header);

		$file = SITE_PATH.'view/'.$name.".php";

		if(is_readable($file)){
			if(isset($vars)){
				extract($vars);
			}
			require($file);
		}

		if($this->layout)include_once(SITE_PATH."view/layout/".$this->footer);
	}
}