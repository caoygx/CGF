<?php
$http_host = $_SERVER['HTTP_HOST'];
if(filter_var($http_host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false){
    $domain = $http_host;
}else{
    $arr = explode('.',$http_host);
    $c = count($arr);
    $domain = $arr[$c-2].'.'.$arr[$c-1];
}
define('DOMAIN', $domain);
if(file_exists("pro.txt")){
    define("CONF_ENV","pro");
}elseif(file_exists("test.txt")){
    define("CONF_ENV","test");
}else{
    define("CONF_ENV","dev");
}

define('ROOT',__DIR__);
define('APP_DEBUG',true);
define('APP_NAME', 'Home');
define('APP_PATH',ROOT.'/Application/');
define('RUNTIME_PATH',ROOT.'/Runtime/'); //runtime目录
require '../ThinkPHP/ThinkPHP.php';
