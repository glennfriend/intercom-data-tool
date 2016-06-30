<?php

if (!class_exists('CommandLineWrap_20160629')) {

    /*
        使用那一種 API 串接方式
            - 走 command line 的方式

        範本
            請參考 document

    */
    class CommandLineWrap_20160629
    {
        /**
         *
         */
        protected $config = [];

        /**
         *  取得解析後的錯誤訊息
         */
        protected $error = null;

        /**
         *  取得的原始資料
         */
        protected $output = null;

        /**
         *
         */
        public function __construct()
        {
            $this->config = include("setting.php");
            $this->checkCallFolder();
        }

        // --------------------------------------------------------------------------------
        //  公開使用的 library
        // --------------------------------------------------------------------------------

        /**
         *  呼叫程式做資料交換
         *  如果成功, 會傳回一個 json data
         *  失敗會傳回 false (boolean)
         *
         *  @return json string or false
         */
        public function call($requestResourceKey, Array $data)
        {
            $this->error    = null;
            $this->output   = null;

            $json =
                json_encode([
                    'api'   => $requestResourceKey,
                    'data'  => $data,
                ],
                JSON_PRETTY_PRINT
            );

            $key = $this->getUniqueId();
            $file = $this->config['call_path'] . '/' . $key;
            if (file_exists($file)) {
                // 出現重覆檔案的機率 非常低
                $this->error = 'file name collision';
                return false;
            }

            $result = $this->save($file, $json);
            if (!$result) {
                // 無法建立檔案
                $this->error = 'can not create json file';
                return false;
            }

            $result = $this->exec($key);
            return $result;
        }

        /**
         *  取得錯誤訊息
         *  return string or null
         */
        public function getError()
        {
            return $this->error . "\n";
        }

        /**
         *  發生錯誤時, 取得原始的內容
         *  return string
         */
        public function getOriginOutput()
        {
            return $this->output;
        }

        // --------------------------------------------------------------------------------
        //  private
        // --------------------------------------------------------------------------------

        /**
         *  執行命令
         *  return array or null
         */
        private function exec($key)
        {
            $responseFile = __DIR__ . '/response.php';
            $this->output = shell_exec("php {$responseFile} {$key}");

            $data = json_decode($this->output, true);
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    return $data;
                break;
                case JSON_ERROR_DEPTH:
                    $this->error = 'Maximum stack depth exceeded';
                break;
                case JSON_ERROR_STATE_MISMATCH:
                    $this->error = 'Underflow or the modes mismatch';
                break;
                case JSON_ERROR_CTRL_CHAR:
                    $this->error = 'Unexpected control character found';
                break;
                case JSON_ERROR_SYNTAX:
                    $this->error = 'Syntax error, malformed JSON';
                break;
                case JSON_ERROR_UTF8:
                    $this->error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            }

            return null;
        }

        /**
         *  建立唯一 key
         */
        private function getUniqueId()
        {
            $id = uniqid('', true);
            return str_replace(".", "_", $id) . '.json';
        }

        /**
         *  檢查 temp folder 是否有問題
         */
        private function checkCallFolder()
        {
            $mode = 0777;
            $path = $this->config['call_path'];
            if (!file_exists($path)) {
                mkdir($path, $mode);
                if (!file_exists($path)) {
                    throw new \Exception('CommandLineWrap Error: can not create "call" folder.');
                    exit;
                }
            }
        }

        /**
         *  save content to file
         *      - you can add UTF8 BOM
         */
        private function save($file, $content, $haveBom=false)
        {
            if ($haveBom) {
                $content = chr(0xEF) . chr(0xBB) . chr(0xBF) . $content;
            }
            return file_put_contents($file, $content);
        }

    }
    
}


/**
 *  factory class
 *  使用者不用知道 class name
 *  所以如果內部程式要改名稱, 也不會影響使用者
 */
return new CommandLineWrap_20160629();


