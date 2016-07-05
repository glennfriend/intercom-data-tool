##Wrap API
- Command Line Wrap API
    - 主要提供 command line 的呼叫環境
    - 這是一個內部資料交換使用的 API 媒介程式
- CURL Wrap API
    - 未提供 localhost https 的呼叫環境


##如何使用 Command Line Wrap API
```php
$path = "/var/www/your-project";
$wrap = include("{$path}/tools/command-wrap/request.php");

$params = [
    'account'  => '帳號',
    'password' => '密碼',
];
$result = $wrap->call('/user/get', $params);
if ($wrap->getError()) {
    echo "Error: "          . $wrap->getError();
    echo "Origin Output:\n" . $wrap->getOriginOutput();
    exit;
}
print_r($result);
```
