<?php
namespace App\CommandWrapApi;

class Users extends BaseCommandWrapApi
{

    /**
     *
     */
    public function init()
    {
    }

    /**
     *  @param $data 從客戶傳來的資料
     */
    public function get(Array $data)
    {
        //print_r($data);



        $this->output($data);
        // $this->output($data);
    }




}
