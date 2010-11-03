<?php
require_once('core_path.php');
require_once($core_path);

import('core.routing');

$router = new \Core\Router();
$router->route($_GET['route']);
?>
