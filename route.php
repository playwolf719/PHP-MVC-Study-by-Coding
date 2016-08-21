<?php
require_once("common/MyHelper.php");
/**
 * 获取路由
 */
if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
} else {
    $controller = 'page';
    $action     = 'home';
}
/**
 * 根据路由进行渲染
 */
if($controller){
    call($controller, $action);
}else{
    call('Page', 'home');
}

/**
 * 调用controller中的action方法
 * [call description]
 * @param  [type] $controller [description]
 * @param  [type] $action     [description]
 * @return [type]             [description]
 */
function call($controller, $action) {
    try {
        //扫描controller文件夹中的所有controller文件
        $controller=ucfirst(strtolower($controller) );
        $action=strtolower($action);
        $filepath=dirname(__FILE__)."/controller/".$controller."Controller.php";
        if(!file_exists($filepath)){
            call('Page', 'error');
        }
        require_once('controller/' . $controller . 'Controller.php');
        $controllerClasssName=$controller."Controller";
        $instance=new $controllerClasssName;
        if(!method_exists($instance,$action) ){
            call('Page', 'error');
        }else{
            //触发beforeAction事件
            $instance->beforeAction($action);
            $instance->{$action}();
        }
    } catch (Exception $e) {
        MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    }
}
?>