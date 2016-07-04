<?php
namespace App\CommandWrapApi;
use App\Business\Intercom\Curl;
use App\Model\IntercomApps;
use App\Model\IntercomApp;
use App\Model\IntercomUsers;
use App\Model\IntercomUser;

/*
    程式會讀取 intercom 的資料, 並寫到資料庫中

    流程:
        - 取得的資料如果不存在資料庫, 就寫入
          請處理完這次的所有筆數, 而不是查到一筆存在就離開程式
        - 如果這批資料的最後一筆有寫入資料庫, 還要取得下次的資料 (loop)

    必要滿足的條件:
        - 資料必須要從 新 --> 舊
*/
class Sync extends BaseCommandWrapApi
{

    /**
     *
     */
    public function init()
    {
    }

    /**
     *  跟 intercom 要資料
     *
     *  @param $data 從客戶傳來的資料
     */
    public function now(Array $params)
    {
        // print_r($params);
        
        $id     = null;
        $apiKey = null;
        foreach (conf('intercom.app') as $app) {
            if ($app['id'] === $params['account']) {
                $id     = $app['id'];
                $apiKey = $app['api_key'];
                break;
            }
        }

        if (!$id) {
            $this->putError('system not support the intercom id');
        }

        $curl = new Curl($app['id'], $app['api_key']);

        $intercomApp = $this->syncApp($app['id']);
        if (!$intercomApp) {
            $this->putError('intercom app error!');
        }

        $result = $this->syncUsers($curl, $intercomApp);

        $this->output([
            'result' => $result,
        ]);
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    /**
     *
     */
    private function syncUsers($curl, $intercomApp, $page = 1)
    {

        $result = $curl->userList($page);
        if (!$result || !is_array($result) || !isset($result['users'])) {
            // $this->putError('Error, Can not get data by https://api.intercom.io/');
            return false;
        }

        $intercomUsers = new IntercomUsers();
        foreach ($result['users'] as $user) {

            set_time_limit(30);

            // false -> 資料庫已存在資料, 不需要新增
            // true  -> 資料庫無資料, 要新增
            $isAddIntercomUser = false;

            $intercomUser = $intercomUsers->getIntercomUserByItemId($user['id']);
            if (!$intercomUser) {
                $isAddIntercomUser = true;
                $intercomUser = new IntercomUser();
                $intercomUser->setIntercomAppId ( $intercomApp->getId() );
                $intercomUser->setItemId        ( $user['id']           );
                $intercomUser->setItemUserId    ( $user['user_id']      );
                $intercomUser->setEmail         ( $user['email']        );
                $intercomUser->setOriginContent ( serialize($user)      );
                $intercomUser->setCreatedAt     ( $user['created_at']   );
                $intercomUser->setUpdatedAt     ( $user['updated_at']   );
                $intercomUsers->addIntercomUser($intercomUser);
            }
        }

        // true -> 這次從 intercom API 取得資料的最後一筆有被寫入資料庫
        // 表示要繼續呼叫 API, 繼續寫入資料
        if ($isAddIntercomUser) {
            $this->syncUsers($curl, $intercomApp, $page+1);
        }

        return true;
    }

    /**
     *  
     */
    private function syncApp($account)
    {
        $account = trim($account);
        $intercomApps = new IntercomApps;

        $intercomApp = $intercomApps->getIntercomAppByAccount($account);
        if ($intercomApp) {
            return $intercomApp;
        }

        $intercomApp = new IntercomApp;
        $intercomApp->setAccount($account);
        $intercomApps->addIntercomApp($intercomApp);
        return $intercomApp;
    }

}
