<?php
namespace App\Model;
use App\Model\IntercomUser as IntercomUser;

/**
 *
 */
class IntercomUsers extends \ZendModel
{
    /**
     *  每一筆資料 的快取
     */
    const CACHE_INTERCOM_USER = 'cache_intercom_user';

    /**
     *  intercom 自身的 id
     */
    const CACHE_INTERCOM_USER_ITEM_ID = 'cache_intercom_user_item_id';

    /**
     *  table name
     */
    protected $tableName = 'intercom_users';

    /**
     *  get method
     */
    protected $getMethod = 'getIntercomUser';

    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow($row)
    {
        $object = new IntercomUser();
        $object->setId                   ( $row['id']                           );
        $object->setIntercomAppId        ( $row['intercom_app_id']              );
        $object->setItemId               ( $row['item_id']                      );
        $object->setItemUserId           ( $row['item_user_id']                 );
        $object->setEmail                ( $row['email']                        );
        $object->setOriginContent        ( unserialize($row['origin_content'])  );
        $object->setProperties           ( unserialize($row['properties'])      );
        $object->setCreatedAt            ( strtotime($row['created_at'])        );
        $object->setUpdatedAt            ( strtotime($row['updated_at'])        );
        return $object;
    }

    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache(IntercomUser $object)
    {
        if ($object->getId() <= 0) {
            return;
        }

        $cacheKey = $this->getFullCacheKey($object->getId(), IntercomUsers::CACHE_INTERCOM_USER);
        CacheBrg::remove($cacheKey);

        $cacheKey = $this->getFullCacheKey($object->getItemId(), IntercomUsers::CACHE_INTERCOM_USER_ITEM_ID);
        CacheBrg::remove($cacheKey);
    }

    /* ================================================================================
        basic write function
    ================================================================================ */

    /**
     *  add
     *  @param IntercomUser object
     *  @return insert id or false
     */
    public function addIntercomUser(IntercomUser $object)
    {
        $insertId = $this->addObject($object, true);
        if (!$insertId) {
            return false;
        }

        $object = $this->getIntercomUser($insertId);
        if (!$object) {
            return false;
        }

        return $insertId;
    }

    /**
     *  update
     *  @param IntercomUser object
     *  @return int
     */
    public function updateIntercomUser(IntercomUser $object)
    {
        $result = $this->updateObject($object);
        if (!$result) {
            return 0;
        }

        $this->removeCache($object);
        return (int) $result;
    }

    /**
     *  delete
     *  @param int id
     *  @return boolean
     */
    public function deleteIntercomUser($id)
    {
        $object = $this->getIntercomUser($id);
        if (!$object) {
            // 原本資料就不存在, 預期會是 true 的值
            return true;
        }
        if (!$this->deleteObject($id)) {
            return false;
        }

        $this->removeCache($object);
        return true;
    }

    /* ================================================================================
        basic read function
    ================================================================================ */

    /**
     *  get IntercomUser by id
     *  @param  int id
     *  @return object or false
     */
    public function getIntercomUser($id)
    {
        $object = $this->getObject('id', $id, IntercomUsers::CACHE_INTERCOM_USER);
        if (!$object) {
            return false;
        }
        return $object;
    }

    /**
     *  get IntercomUser by item id
     *  @param  int item id
     *  @return object or false
     */
    public function getIntercomUserByItemId($itemId)
    {
        $object = $this->getObject('item_id', $itemId, IntercomUsers::CACHE_INTERCOM_USER_ITEM_ID);
        if (!$object) {
            return false;
        }
        return $object;
    }

    /* ================================================================================
        find IntercomUsers and get count
        通用型的 find, 管理界面使用
    ================================================================================ */

    /**
     *  find many IntercomUser
     *  @param  option array
     *  @return objects or empty array
     */
    public function findIntercomUsers(Array $values, $opt=[])
    {
        $opt += [
            'serverType' => \ZendModel::SERVER_TYPE_MASTER,
            'page' => 1,
            'order' => [
                'id' => 'DESC',
            ],
        ];
        return $this->findIntercomUsersReal($values, $opt);
    }

    /**
     *  get count by "findIntercomUsers" method
     *  @return int
     */
    public function numFindIntercomUsers($values, $opt=[])
    {
        $opt += [
            'serverType' => \ZendModel::SERVER_TYPE_MASTER,
        ];
        return $this->findIntercomUsersReal($values, $opt, true);
    }

    /**
     *  findIntercomUsers option
     *
     *  find 處理邏輯
     *      字串比對 name = "value"
     *          "name" => "john"    => 只顯示名字完全比對為 "john" 的資料
     *          "name" => ""        => 顯示沒有名字的資料
     *          "name" => null      => 略過欄位, 資料的比對
     *
     *      字串搜尋 name like %value%
     *          "name" => "jonh"    => 只要名字中有 john 就顯示該資料
     *          "name" => ""        => 全部顯示 --> like %%
     *          "name" => null      => 略過欄位, 資料的比對
     *
     *  @return objects or record total
     */
    protected function findIntercomUsersReal(Array $vals, $opt=[], $isGetCount=false)
    {
        // validate 欄位 白名單
        $map = [
            'id'                    => 'id',
            'intercomAppId'         => 'intercom_app_id',
            'itemId'                => 'item_id',
            'itemUserId'            => 'item_user_id',
            'email'                 => 'email',
            'originContent'         => 'origin_content',
            'properties'            => 'properties',
            'createdAt'             => 'created_at',
            'updatedAt'             => 'updated_at',
        ];
        \ZendModelWhiteListHelper::perform($vals, $map, $opt);
        $select = $this->getDbSelect();


        if (isset($vals['id'])) {
            $select->where->and->equalTo($map['id'], $vals['id']);
        }
        if (isset($vals['intercomAppId'])) {
            $select->where->and->equalTo($map['intercomAppId'], $vals['intercomAppId']);
        }
        if (isset($vals['itemId'])) {
            $select->where->and->equalTo($map['itemId'], $vals['itemId']);
        }
        if (isset($vals['itemUserId'])) {
            $select->where->and->equalTo($map['itemUserId'], $vals['itemUserId']);
        }
        if (isset($vals['email'])) {
            $select->where->and->equalTo($map['email'], $vals['email']);
        }

        if (!$isGetCount) {
            return $this->findObjects($select, $opt);
        }
        return $this->numFindObjects($select);
    }

    /* ================================================================================
        extends
    ================================================================================ */

}
