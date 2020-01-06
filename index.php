<?php

define('APP_PATH', dirname(__FILE__));
define('APP_NAME', 'my_yaf');

$config = \Yaconf::get(APP_NAME . '_app');

$config['application']['directory'] = APP_PATH . $config['application']['directory'];
$config['log_path'] = APP_PATH . $config['log_path'];
$application = new Yaf\Application($config);

$application->bootstrap()->run();
?>
