<?php
// Find root folder
$root = dirname(dirname(__FILE__));

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));

// Add application and library to include path
set_include_path(
    APPLICATION_PATH . '/../library'
    . PATH_SEPARATOR . get_include_path()
);

require_once '../application/Bootstrap.php';

$boot = new Bootstrap();
$boot->run();
	
?>
