<?php

if (!class_exists('IntercomDataMiddleware_CommandLineWrap')) {

    /*
        使用那一種 API 串接方式
            - 走 command line 的方式

        範本
            請參考 document

    */
    class IntercomDataMiddleware_CommandLineWrap
    {
        /**
         *
         */
        protected $config = [];

        /**
         *
         */
        protected $error = null;

        /**
         *
         */
        public function __construct()
        {
            $this->config = include("command.config.php");
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
        public function call($type, Array $data)
        {
            $json =
                json_encode([
                    'type' => $type,
                    'data' => $data,
                ],
                JSON_PRETTY_PRINT
            );

            $key = $this->getUniqueId();
            $file = $this->config['call_path'] . '/' . $key;
            if (file_exists($file)) {
                // 出現重覆檔案的機率 非常低
                return false;
            }

            $result = $this->save($file, $json);
            if (!$result) {
                // 無法建立檔案
                return false;
            }

            $data = $this->exec($key);
            if (!$data) {
                return null;
            }

            return $data;
        }

        /**
         *  return string or null
         */
        public function getError()
        {
            return $this->error;
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
            $this->error = null;

            $projectPath = $this->config['project_path'];
            $output = shell_exec("php {$projectPath}/tools/command-response.php {$key}");

            $data = json_decode($output, true);
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
                    throw new \Exception('IntercomDataMiddleware_CommandLineWrap Error: can not create "call" folder.');
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
 *  使用 Closure 的方式
 *  使用者不用知道 class name
 *  所以如果內部程式要改名稱, 也不會影響使用者
 */
return function()
{
    return new IntercomDataMiddleware_CommandLineWrap();
};

