<?php
/**
 * 调用controller中的action方法
 * [call description]
 * @param  [type] $controller [description]
 * @param  [type] $action     [description]
 * @return [type]             [description]
 */
function call($controller, $action) {
    $controller=ucfirst(strtolower($controller) );
    require_once('controllers/' . $controller . 'Controller.php');
    $controllerClasssName=$controller."Controller";
    $instance=new $controllerClasssName;
    if(!method_exists($instance,$action)){
        call('Page', 'error');
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

//扫描controller文件夹中素有controller文件
$controller_file_array=scandir("controller");
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