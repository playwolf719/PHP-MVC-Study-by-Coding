<?php
/**
 * 引入数据库
 */
require_once('connection.php');

if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
} else {
    $controller = 'page';
    $action     = 'home';
}

/**
 * 引入路由
 */
require_once('route.php');
?>