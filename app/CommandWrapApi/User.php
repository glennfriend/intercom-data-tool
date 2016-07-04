<?php
namespace App\CommandWrapApi;
use App\Model\IntercomApps;
use App\Model\IntercomUsers;
use App\Business\Intercom\InfoOutputPack;

class User extends BaseCommandWrapApi
{

    /**
     *
     */
    public function init()
    {
    }

    /**
     *  get by intercom id
     */
    public function getByItemId(Array $params)
    {
        $intercomApp = $this->getIntercomAppByParams($params);
        if (!isset($params['item_id'])) {
            $this->putError('param `item_id` not found');
        }

        $fields = array_filter([
            'intercomAppId' => $intercomApp->getId(),
            'itemId'        => $params['item_id'],
        ]);

        $intercomUsers   = new IntercomUsers();
        $myIntercomUsers = $intercomUsers->findIntercomUsers($fields);
        if (!isset($myIntercomUsers[0])) {
            $this->output([
                'user' => [],
            ]);
            return;
        }

        $intercomUser = $myIntercomUsers[0];
        InfoOutputPack::add('intercom_user', $intercomUser->getId());
        $this->output([
            'user' => InfoOutputPack::fetchOne('intercom_user')
        ]);
    }

    /**
     *  get by intercom user id
     */
    public function getByItemUserId(Array $params)
    {
        $intercomApp = $this->getIntercomAppByParams($params);
        if (!isset($params['item_user_id'])) {
            $this->putError('param `item_user_id` not found');
        }

        $fields = array_filter([
            'intercomAppId' => $intercomApp->getId(),
            'itemUserId'    => $params['item_user_id'],
        ]);
        $intercomUsers   = new IntercomUsers();
        $myIntercomUsers = $intercomUsers->findIntercomUsers($fields);
        if (!isset($myIntercomUsers[0])) {
            $this->output([
                'user' => [],
            ]);
            return;
        }

        $intercomUser = $myIntercomUsers[0];
        InfoOutputPack::add('intercom_user', $intercomUser->getId());
        $this->output([
            'user' => InfoOutputPack::fetchOne('intercom_user')
        ]);
    }

    /**
     *  get by email
     */
    public function getByEmail(Array $params)
    {
        $intercomApp = $this->getIntercomAppByParams($params);
        if (!isset($params['email'])) {
            $this->putError('param `email` not found');
        }

        $fields = array_filter([
            'intercomAppId' => $intercomApp->getId(),
            'email'         => $params['email'],
        ]);
        $intercomUsers   = new IntercomUsers();
        $myIntercomUsers = $intercomUsers->findIntercomUsers($fields);
        if (!isset($myIntercomUsers[0])) {
            $this->output([
                'user' => [],
            ]);
            return;
        }

        $intercomUser = $myIntercomUsers[0];
        InfoOutputPack::add('intercom_user', $intercomUser->getId());
        $this->output([
            'user' => InfoOutputPack::fetchOne('intercom_user')
        ]);
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    /**
     *
     */
    private function getIntercomAppByParams($params)
    {
        if (!isset($params['account'])) {
            $this->putError('param `account` not found');
        }

        $intercomApps = new IntercomApps();
        $intercomApp = $intercomApps->getIntercomAppByAccount($params['account']);
        if (!$intercomApp) {
            $this->putError('intercom-app not found');
        }

        return $intercomApp;
    }

}
