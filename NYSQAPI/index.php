<?php
use Yaf\Application;
use Yaf\Exception;
//session_start();
define("APP_PATH",  realpath(dirname(__FILE__) . '/')); /* 指向public的上一级 */
define('ROOT_PATH', str_replace('\\', '/', dirname(__DIR__)) . '/');
define("APP_URI","http://192.168.0.107");
// 定义当前请求的系统常量
define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);
define('IS_AJAX',       ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false);
//error_reporting(0);
$app  = new Application(APP_PATH . "/conf/application.ini",ini_get('yaf.environ'));
$app->bootstrap()->run();





