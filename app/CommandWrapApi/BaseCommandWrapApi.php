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

}
