<?php
namespace App\CommandWrapApi;
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
     *  get by id
     */
    public function getByItemId(Array $params)
    {
        if (!isset($params['item_id'])) {
            $this->putError('param `item_id` not found');
        }

        $fields = array_filter([
            'itemId' => $params['item_id'],
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
     *  get by user id
     */
    public function getByItemUserId(Array $params)
    {
        if (!isset($params['item_user_id'])) {
            $this->putError('param `item_user_id` not found');
        }

        $fields = array_filter([
            'itemUserId' => $params['item_user_id'],
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
        if (!isset($params['email'])) {
            $this->putError('param `email` not found');
        }

        $fields = array_filter([
            'email' => $params['email'],
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

}
