<?php
if (extension_loaded('xhprof')) {
    include_once '/usr/share/php/xhprof_lib/utils/xhprof_lib.php';
    include_once '/usr/share/php/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}

require_once('core_path.php');
require_once(CORE_PATH . '/core.php');

import('core.routing');

$router = new \Core\Router();
$router->route(URI);

if (extension_loaded('xhprof')) {
    $profiler_namespace = 'core';  // namespace for your application
    $xhprof_data = xhprof_disable();
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);
 
    // url to the XHProf UI libraries (change the host name and path)
    $profiler_url = sprintf('http://trouble.0xf.nl/xhprof/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
    echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
}