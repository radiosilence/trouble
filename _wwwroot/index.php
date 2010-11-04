<?php
require_once('core_path.php');
require_once(CORE_PATH . '/core.php');

import('core.routing');

$router = new \Core\Router();
$router->route($_GET['route']);
?>
