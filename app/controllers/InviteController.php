<?php


namespace MyApp\Controllers;


use MyApp\Models\Account;
use MyApp\Models\Invite;
use Phalcon\Mvc\Dispatcher;

class InviteController extends ControllerBase
{

    private $inviteModel;

    public function initialize()
    {
        parent::initialize();
        $this->inviteModel = new Invite();
    }


    /**
     * 当前用户的邀请码
     */
    public function meAction()
    {
        $access_token = $this->request->get('access_token', 'string');
        $zone = $this->request->get('zone', 'alphanum');
        $user_id = $this->request->get('user_id', 'alphanum');

        $account = Account::verifyAccessToken($access_token);
        if (!$account) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'token error'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }
        if (!$zone || !$user_id) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'no zone nor user'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        $code = $this->inviteModel->getMyCode($zone, $user_id);
        if ($code) {
            $code = $code['code'];
        }
        else {
            $code = strtolower(str_random(4)) . base_convert($account['account_id'], 10, 36);
            $this->inviteModel->setMyCode($zone, $user_id, $code);
        }

        $this->response->setJsonContent(
            ['code' => 0, 'msg' => 'success', 'data' => ['code' => $code]],
            JSON_UNESCAPED_UNICODE)->send();
        exit();
    }

}