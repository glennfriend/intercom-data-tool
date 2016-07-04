<?php
namespace App\Model;
use App\Model\IntercomApp as IntercomApp;

/**
 *
 */
class IntercomApps extends \ZendModel
{
    /**
     *  每一筆資料 的快取
     */
    const CACHE_INTERCOM_APP = 'cache_intercom_app';

    /**
     *
     */
    const CACHE_INTERCOM_APP_ACCOUNT = 'cache_intercom_app_account';

    /**
     *  table name
     */
    protected $tableName = 'intercom_apps';

    /**
     *  get method
     */
    protected $getMethod = 'getIntercomApp';

    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow($row)
    {
        $object = new IntercomApp();
        $object->setId          ( $row['id']                        );
        $object->setAccount     ( $row['account']                   );
        $object->setProperties  ( unserialize($row['properties'])   );
        return $object;
    }

    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache(IntercomApp $object)
    {
        if ($object->getId() <= 0) {
            return;
        }

        $cacheKey = $this->getFullCacheKey($object->getId(), IntercomApps::CACHE_INTERCOM_APP);
        CacheBrg::remove($cacheKey);

        $cacheKey = $this->getFullCacheKey($object->getAccount(), IntercomApps::CACHE_INTERCOM_APP_ACCOUNT);
        CacheBrg::remove($cacheKey);
    }

    /* ================================================================================
        basic write function
    ================================================================================ */

    /**
     *  add
     *  @param IntercomApp object
     *  @return insert id or false
     */
    public function addIntercomApp(IntercomApp $object)
    {
        $insertId = $this->addObject($object, true);
        if (!$insertId) {
            return false;
        }

        $object = $this->getIntercomApp($insertId);
        if (!$object) {
            return false;
        }

        return $insertId;
    }

    /**
     *  update
     *  @param IntercomApp object
     *  @return int
     */
    public function updateIntercomApp(IntercomApp $object)
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
    public function deleteIntercomApp(int $id)
    {
        $object = $this->getIntercomApp($id);
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
     *  get by id
     *  @param  int id
     *  @return object or false
     */
    public function getIntercomApp($id)
    {
        $object = $this->getObject('id', $id, IntercomApps::CACHE_INTERCOM_APP);
        if (!$object) {
            return false;
        }
        return $object;
    }

    /**
     *  get by account
     *  @param  string account
     *  @return object or false
     */
    public function getIntercomAppByAccount($account)
    {
        $object = $this->getObject('account', $account, IntercomApps::CACHE_INTERCOM_APP_ACCOUNT);
        if (!$object) {
            return false;
        }
        return $object;
    }

    /* ================================================================================
        extends
    ================================================================================ */

}
