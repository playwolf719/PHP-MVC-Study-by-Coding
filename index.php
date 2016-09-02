<?php

define('SITE_PATH',realpath(dirname(__FILE__)).'/');
define('SITE','http://182.92.6.59/dcb/mvc/index.php');
/**
 * 引入基类
 */
require_once("base/View.php");
require_once("base/Controller.php");
require_once("base/Model.php");

/**
 * 引入数据库
 */
require_once('connection.php');

require __DIR__  . '/vendor/autoload.php';

/**
 * 引入路由
 */
require_once('route.php');
?>