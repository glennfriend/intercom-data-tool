<?php
namespace App\Model;
use App\Model\IntercomUsers as IntercomUsers;

/**
 *  IntercomUser
 *
 */
class IntercomUser extends \BaseObject
{

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        return [
            'id' => [
                'type'    => 'integer',
                'filters' => ['intval'],
            ],
            'intercom_app_id' => [
                'type'    => 'integer',
                'filters' => ['intval'],
            ],
            'item_id' => [
                'type'    => 'string',
                'filters' => ['strip_tags','trim'],
            ],
            'item_user_id' => [
                'type'    => 'string',
                'filters' => ['strip_tags','trim'],
            ],
            'email' => [
                'type'    => 'string',
                'filters' => ['strip_tags','trim'],
            ],
            'origin_content' => [
                'type'    => 'string',
                'filters' => ['strip_tags','trim'],
            ],
            'properties' => [
                'type'    => 'string',
                'filters' => ['arrayval'],
            ],
            'created_at' => [
                'type'    => 'timestamp',
                'filters' => ['dateval'],
                'value'   => strtotime('1970-01-01'),
            ],
            'updated_at' => [
                'type'    => 'timestamp',
                'filters' => ['dateval'],
                'value'   => strtotime('1970-01-01'),
            ],
        ];
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method rewrite or extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  Disabled methods
     *  @return array()
     */
    public static function getDisabledMethods()
    {
        return [];
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */



    /* ------------------------------------------------------------------------------------------------------------------------
        lazy loading methods
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  get intercomApp object
     *
     *  @param isCacheBuffer , is store object
     *  @return object or null
     */
    public function getIntercomApp($isCacheBuffer=true)
    {
        if (!$isCacheBuffer) {
            $this->_intercomApp = null;
        }
        if (isset($this->_intercomApp)) {
            return $this->_intercomApp;
        }

        $intercomAppId = $this->getIntercomAppId();
        if (!$intercomAppId) {
            return null;
        }
        $intercomApps = new IntercomApps();
        $intercomApp = $intercomApps->getIntercomApp($intercomAppId);

        if ($isCacheBuffer) {
            $this->_intercomApp = $intercomApp;
        }
        return $intercomApp;
    }

}
