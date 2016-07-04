<?php
namespace App\Business\Intercom;

class Curl
{

    public function __construct($id, $apiKey)
    {
        $this->userPwd = "{$id}:{$apiKey}";
    }

    /**
     *
     */
    public function userList($page=1)
    {
        $url = 'users?per_page=50';
        if ($page) {
            $url .= '&page=' . $page;
        }

        $result = $this->curl($url);
        if ($this->isError()) {
            return $this->getError();
        }
        return $result;
    }

    /*
    public function addUser()
    {
        $item = [
            'user_id'               => 'appointment_5',
            'email'                 => 'johnny@mail',
            'custom_attributes'     => [
                'wedding_date'  => "2016-06-01",
                'age'           => 15,
                'interest'      => 'ball,food,sport',
            ],
        ];

        $result = $this->curl('users', $item);
        if ($this->isError()) {
            return $this->getError();
        }
        return $result;
    }
    */

    // --------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------

    /**
     *
     */
    public function getError()
    {
        return [
            'error' => [
                'message' => $this->getJsonError()
            ]
        ];
    }

    /**
     *
     */
    public function makeUrl($key)
    {
        return "https://api.intercom.io/" . $key;
    }

    /**
     *
     */
    public function curl($url, $arrItem=[])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->makeUrl($url));
        curl_setopt($ch, CURLOPT_USERPWD, $this->userPwd);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($arrItem) {
            // 有資料表示為 post
            $json = json_encode($arrItem);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        }

        $headers = array();
        $headers[] = "Accept: application/json";
        // $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'error' => curl_error($ch)
            ];
        }
        curl_close ($ch);

        return json_decode($output, true);
    }

    /**
     *
     */
    public function isError()
    {
        $message = $this->getJsonError();
        if ($message) {
            return true;
        }
        return false;
    }

    /**
     *  get json decode error message
     *  empty string is success
     *
     *  @return string
     */
    public function getJsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return '';
                break;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
        }
        return 'Unknown error';
    }

}

