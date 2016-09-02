<?php
/**
* Base View
*/
class View
{
	public $header="header.php";
	public $footer="footer.php";
	
	function __construct()
    {	

    }

    function render($name,array $vars = null,$layout=true){
		if($layout)include_once(SITE_PATH."view/layout/".$this->header);

		$file = SITE_PATH.'view/'.$name.".php";

		if(is_readable($file)){
			if(isset($vars)){
				extract($vars);
			}
			require($file);
		}

		if($layout)include_once(SITE_PATH."view/layout/".$this->footer);
    }
}