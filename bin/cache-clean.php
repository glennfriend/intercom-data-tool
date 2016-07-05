<?php
/*
    clean cache
*/

$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);

// --------------------------------------------------------------------------------
//  start
// --------------------------------------------------------------------------------

di('cache')->flush();
