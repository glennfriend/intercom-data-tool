<?php
$basePath = dirname(__DIR__);
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
//  該程式必須配合 command-request.php 來做改變
// --------------------------------------------------------------------------------
//$api = include("{$basePath}/tools/command-request.php");
//show($api());

$config = include("{$basePath}/tools/command.config.php");
$response = new IntercomDataMiddleware_CommandResponse($config);
echo $response->fetch($key);

class IntercomDataMiddleware_CommandResponse
{
    /**
     *
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->checkCompleteFolder();
    }

    /**
     *  取得資料, 並歸檔到資料夾
     */
    public function fetch($key)
    {
        $content = $this->getContentByKey($key);
        $this->fileAway($key);
        return $content;
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    /**
     *
     */
    private function getContentByKey($key)
    {
        $file = $this->config['call_path'] . '/' . $key;
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    /**
     *
     */
    private function fileAway($key)
    {
        $from = $this->config['call_path']     . '/' . $key;
        $to   = $this->config['complete_path'] . '/' . $key;
        if (!file_exists($from)) {
            return;
        }
        rename($from, $to);
    }

    /**
     *  檢查 temp folder 是否有問題
     */
    private function checkCompleteFolder()
    {
        $mode = 0777;
        $path = $this->config['complete_path'];
        if (!file_exists($path)) {
            mkdir($path, $mode, true);
            if (!file_exists($path)) {
                throw new \Exception('CommandResponse Error: can not create "complete" folder.');
                exit;
            }
        }
    }

}
