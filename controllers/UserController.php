<?php
require_once("base/Controller.php");
/**
 * UserController
 */
class UserController extends Controller{

    public function index() {
      	// we store all the posts in a variable
      	$this->layout=false;
        // echo json_encode(array("test"));
        return "views/user/user.php";
    
    }


    public function register() {
      	// we store all the posts in a variable
        // echo json_encode(array("test"));
        return "views/user/user.php";
    
    }
}
?>