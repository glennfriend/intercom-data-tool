<?php
namespace App\Business\Intercom;
use App\Model\IntercomUsers;
use App\Model\IntercomUser;

/**
 *  將 dbojbect 做一個標準的通用輸出
 *      - 利用 id 取得唯一值, 避免重覆輸出
 *      - 新程式只是為了 方便 & 統一 & 快速輸出
 *        如果有過多或過少的資料, 可以個別輸出, 不需使用該程式
 *
 *  NOTE: 
 *      身為一個最後輸出資料的 module class
 *      開發者 應該在處理完所有 add,update,delete 行為之後
 *      才將資料寫入這裡
 *      不然會導致最終資料有誤
 *
 */
class InfoOutputPack
{

    /**
     *
     */
    protected static $data;


    /**
     *  add info
     */
    public static function add($type, $id)
    {
        switch ($type) {
            case 'intercom_user':
                self::addIntercomUser($id);
                break;
            default:
                return false;
        }
    }

    /**
     *  get one info
     */
    public static function fetchOne($type)
    {
        if (!isset(self::$data[$type])) {
            return [];
        }
        $arr = array_values(self::$data[$type]);
        return $arr[0];
    }

    /**
     *  get all infos
     */
    public static function fetchAll($type)
    {
        if (!isset(self::$data[$type])) {
            return [];
        }
        return array_values(self::$data[$type]);
    }

    // --------------------------------------------------------------------------------
    //  get data
    // --------------------------------------------------------------------------------

    /**
     *  intercom_user
     */
    private static function addIntercomUser($id)
    {
        static $intercomUsers;
        if (!$intercomUsers) {
            $intercomUsers = new IntercomUsers();
        }

        $object = $intercomUsers->getIntercomUser($id);
        if (!$object) {
            return;
        }

        self::$data['intercom_user'][$object->getId()] = [
            'item_id'           => $object->getItemId(),
            'item_user_id'      => $object->getItemUserId(),
            'email'             => $object->getEmail(),
            'origin_content'    => $object->getOriginContent(),
        ];
    }

}

