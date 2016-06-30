<?php
$basePath = dirname(dirname(__DIR__));
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);

// --------------------------------------------------------------------------------
//  validate
// --------------------------------------------------------------------------------
if (!isset($argv)) {
    exit;
}
if (!is_array($argv)) {
    exit;
}
if (!isset($argv[1])) {
    exit;
}

$key = $argv[1];

// --------------------------------------------------------------------------------
//  該程式必須配合 request.php 來做改變
// --------------------------------------------------------------------------------
include_once('CommandResponse.php');
$config = include("{$basePath}/tools/command-wrap/setting.php");
$response = new CommandResponse_20160629($config);
$result = $response->fetch($key);
$params = json_decode($result, true);

if (!$params['api']) {
    echo json_encode([
        'error' => 'Lack for "api" param'
    ]);
    exit;
}
if (!preg_match('/^[a-zA-Z0-9_-]+$/', $params['api'])) {
    echo json_encode([
        'error' => '"api" param error'
    ]);
    exit;
}

switch ($params['api']) {
    case 'get':
        (new App\CommandWrapApi\Users())->getAll($params);
    break;
    default:
        echo json_encode([
            'error' => 'API not found'
        ]);
    break;
}

echo "\n";



