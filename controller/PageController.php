<?php
/**
 * PageController
 */
class PageController extends Controller{
    
    // public $layout=false;

    public function home() {
        $first_name = 'Deng';
        $last_name  = 'Benjamin';
        $this->render('page/home',array('first_name' =>$first_name, "last_name"=>$last_name) );
    }

    public function error() {
        $this->render( 'page/error');
    }
}
?>