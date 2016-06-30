<?php

class CommandResponse_20160629
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
     *  將傳遞的訊息 歸檔
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
