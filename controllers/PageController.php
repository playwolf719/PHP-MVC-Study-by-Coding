<?php
require_once("base/Controller.php");
class PageController extends Controller{
    
    public $layout=false;

    public function home() {
        $first_name = 'Jon';
        $last_name  = 'Snow';
        require_once('views/pages/home.php');
    }

    public function error() {
        require_once('views/pages/error.php');
    }
}
?>