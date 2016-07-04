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

$jsonString = $response->fetch($key);
$data       = json_decode($jsonString, true);
if (!$data['api']) {
    echo json_encode([
        'error' => 'Lack for "api" param'
    ]);
    exit;
}
if (!preg_match('/^[a-zA-Z0-9_\-\/]+$/', $data['api'])) {
    echo json_encode([
        'error' => '"api" param error'
    ]);
    exit;
}

$params = $data['data'];
switch ($data['api']) {
    case '/sync/now':
        (new App\CommandWrapApi\Sync())->now($params);
    break;
    case '/user/getByItemId':
        (new App\CommandWrapApi\User())->getByItemId($params);
    break;
    case '/user/getByItemUserId':
        (new App\CommandWrapApi\User())->getByItemUserId($params);
    break;
    case '/user/getByEmail':
        (new App\CommandWrapApi\User())->getByEmail($params);
    break;
    case '/help':
        $desc = <<<EOD
/sync/now               => 對 intercom API 做資料同步
/user/getByItemId       => get user by Intercom-id
/user/getByItemUserId   => get user by Intercom-user-id
/user/getByEmail        => get user by email
\n
EOD;
        echo json_encode($desc);
    break;
    default:
        echo json_encode([
            'error' => 'API not found'
        ]);
    break;
}

echo "\n";



