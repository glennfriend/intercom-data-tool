<?php
$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);

use App\Model\Users;
use App\Model\User;

// --------------------------------------------------------------------------------
//  setting
// --------------------------------------------------------------------------------
$userItems = [
    [
        'account'   => 'admin',
        'password'  => '',
        'roles'     => 'manager,login',
    ],
    [
        'account'   => 'guest',
        'password'  => '',
        'roles'     => 'normal,login',
    ],
];

// --------------------------------------------------------------------------------
//  start
// --------------------------------------------------------------------------------

$users = new Users();
foreach ($userItems as $data) {

    if (!$data['password']) {
        echo "[error] `{$data['account']}` password is empty\n";
        continue;
    }

    $user = new User();
    $user->setAccount(      $data['account']                    );
    $user->setPassword(     $data['password']                   );
    $user->setEmail(        $data['account'] . '@localhost.com' );
    $user->setRoleNames(    $data['roles']                      );
    $user->setStatus(       User::STATUS_ENABLED                );
    
    $result = $users->addUser($user);
    if ($result) {
        echo "[success] Add `{$data['account']}` success\n";
    }
    else {
        echo "[error] Add `{$data['account']}` fail\n";
    }

}
