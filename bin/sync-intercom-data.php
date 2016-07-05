<?php
$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);

// --------------------------------------------------------------------------------
//  start
// --------------------------------------------------------------------------------

$apps = conf('intercom.app');
foreach ($apps as $app) {
    $params = [
        'account' => $app['id']
    ];
    (new App\CommandWrapApi\Sync())->now($params);
    echo "\n";
}

