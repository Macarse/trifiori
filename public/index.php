<?php
// Find root folder
$root = dirname(dirname(__FILE__));

set_include_path($root.'/application' . PATH_SEPARATOR
    .$root.'/library' . PATH_SEPARATOR
    . get_include_path()
);

require_once 'Bootstrap.php';

$boot = new Bootstrap($root);
$boot->run();

?>
