<?php
require_once("base/Controller.php");
/**
 * UserController
 */
class UserController extends Controller{
    public $layout=false;

    public function index() {
      	// we store all the posts in a variable
      	$this->layout=false;
        // echo json_encode(array("test"));
        return "views/user/user.php";
    }

    /**
     * 注册
     * @return [type] [description]
     */
    public function register() {
    
    }
}
?>