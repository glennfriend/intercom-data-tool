##Wrap API
- 能支援多個不同帳號的 intercom 服務
- 多個不同帳號與服務，建立在同一個資料表
- 這是有獨立功能的程式, 該 project 本身必須要做設定
- Command Line Wrap API
    - 主要提供 command line 的呼叫環境
    - 這是一個內部資料交換使用的 API 媒介程式
- CURL Wrap API
    - 未提供 localhost https 的呼叫環境


##如何使用 Command Line Wrap API
```php
$path = "/var/www/your-project";
$wrap = include("{$path}/tools/command-wrap/request.php");

$data = [
    'account' => '某個帳號',
];

$result = $wrap->call('get', $data);
if ($result) {
    print_r($result);
}
else {
    echo "Error: "          . $wrap->getError();
    echo "Origin Output:\n" . $wrap->getOriginOutput();
}
```
