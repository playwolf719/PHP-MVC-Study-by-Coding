<?php
/*
call path:index.php->layout.php->routes.php
*/
function call($controller, $action) {
    $controller=ucfirst(strtolower($controller) );
    require_once('controllers/' . $controller . 'Controller.php');
    $controllerClasssName=$controller."Controller";
    $instance=new $controllerClasssName;
    if(!method_exists($instance,$action)){
        call('pages', 'error');
    }
    $output=$instance->{ $action }();
    if($instance->layout){
        include_once("views/layout/header.php");
    }
    include_once($output);
    if($instance->layout){
        include_once("views/layout/footer.php");
    }
}

// we're adding an entry for the new controller and its actions
$controller_file_array=scandir("controllers");
if($controller){
    $controller=ucfirst(strtolower($controller) );
    if(in_array($controller."Controller.php",$controller_file_array) ){
        call($controller, $action);
    }else{
        call('Page', 'error');
    }
}else{
    call('Page', 'home');
}
?>