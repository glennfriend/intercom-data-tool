<?php
namespace App\CommandWrapApi;

/**
 *
 */
class BaseCommandWrapApi
{

    /**
     *
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    public function init()
    {
        // please rewrite
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    /**
     *  輸出為 json
     */
    protected function output(Array $data)
    {
        echo json_encode($data);
    }

    /**
     *  輸出失敗的資訊
     *
     *` @param $errorMessage string, 錯誤訊息
     *  @param $errorKey     string, 錯誤碼
     *
     */
    public function putError($message)
    {
        $output = [
            'error' => $message
        ];
        echo json_encode($output);
        exit;
    }

}
