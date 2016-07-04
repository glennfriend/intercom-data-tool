<?php
namespace App\Model;
use App\Model\IntercomApps as IntercomApps;

/**
 *  IntercomApp
 *
 */
class IntercomApp extends \BaseObject
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
            'account' => [
                'type'    => 'string',
                'filters' => ['strip_tags','trim'],
            ],
            'properties' => [
                'type'    => 'string',
                'filters' => ['arrayval'],
            ],
        ];
    }

    /**
     *  validate
     *  @return messages array()
     */
    public function validate()
    {
        $messages = [];

        if (!$this->getAccount()) {
            $messages['account'] = 'The field is required.';
        }

        return $messages;
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method rewrite or extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /* ------------------------------------------------------------------------------------------------------------------------
        lazy loading methods
    ------------------------------------------------------------------------------------------------------------------------ */


}
