<?php


namespace MyApp\Controllers;


use MyApp\Models\Card;
use MyApp\Models\Utils;
use Phalcon\Mvc\Dispatcher;

class CardController extends ControllerBase
{

    private $cardModel;


    public function initialize()
    {
        parent::initialize();
        $this->cardModel = new Card();
    }

    // 1031019.api.gamehetu.com/card/verify?account_id=xxxxxx(平台账号id)&code=12346(16位)&role_id=1234(角色id)&zone = 1(区服id)
    public function verifyAction()
    {
        $access_token = $this->request->get('access_token');
        $account_id = $this->request->get('account_id', 'alphanum');
        $role_id = $this->request->get('role_id', 'alphanum');
        $zone = $this->request->get('zone', 'alphanum');
        $code = $this->request->get('code', 'alphanum');

        if (!$code) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'no data'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 过滤
        $code = strtolower(str_replace(['-', ' '], '', $code));

        if (strlen($code) < 6) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'card error'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 不存在
        $card = $this->cardModel->getItem($code);
        if (!$card) {
            $this->response->setJsonContent(['code' => 2, 'msg' => 'card isn`t exist'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 已使用
        if ($card['status'] != 1) {
            $this->response->setJsonContent(['code' => 3, 'msg' => 'card is used'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 已过期
        if ($card['expired_in'] < date('Y-m-d H:i:s')) {
            $this->response->setJsonContent(['code' => 4, 'msg' => 'card is expired'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }
        //未开始
        if ($card['start_time'] > date('Y-m-d H:i:s')) {
            $this->response->setJsonContent(['code' => 6, 'msg' => 'card not started'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 使用限制
        if ($card['limit_times']) {
            // 按账号检查
            $times = $this->cardModel->checkTimesByAccountId($card['topic_id'], $account_id);
            if ($times >= $card['limit_times']) {
                $this->response->setJsonContent(['code' => 5, 'msg' => 'used times limit'],
                    JSON_UNESCAPED_UNICODE)->send();
                exit();
            }
        }

        // 区分code使用类型
        $code_limit_times = $card['code_limit_times'];

        if ($code_limit_times > 0) {  // 限制使用次数
            // 通过code检查使用次数
            $code_times = $this->cardModel->checkCodeTimesByCode($card['code']);
            if ($code_times >= $code_limit_times) {
                $this->response->setJsonContent(['code' => 5, 'msg' => 'used times limit'],
                    JSON_UNESCAPED_UNICODE)->send();
                exit();
            }
        }

        switch ($card['type']) {
            case 'cash';
                $data = [
                    'zone' => $zone,
                    'user_id' => $role_id,
                    'amount' => $card['data'],
                    'msg' => $card['title']
                ];
                $result = Utils::rpc('/prop/coin', $data);
                break;

            case 'discount';
                $result = false;
                break;

            case 'register';
                $gift_id = '';
                //$result = true;
                break;

            case 'prop';
                $data = [
                    'zone' => $zone,
                    'user_id' => $role_id,
                    'attach' => $card['data'],
                    'msg' => $card['title']
                ];
                $gift_id = $card['data'];
                $result = Utils::rpc('/prop/attach', $data);
                break;
        }

        /*
        if (!$result) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'failed'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }
        */

        $this->cardModel->cardLogs("{$account_id}", $code, $card['topic_id'], $code_limit_times);
        $this->response->setJsonContent(['code' => 0, 'msg' => 'success', 'gift_id' => $gift_id], JSON_UNESCAPED_UNICODE)->send();
        exit();
    }

    // 专门为伊妮莉丝的先遣测试准备, 不需要检测嘛
    public function verifyqqAction()
    {
        $access_token = $this->request->get('access_token');
        $account_id = $this->request->get('account_id', 'alphanum');
        $role_id = $this->request->get('role_id', 'alphanum');
        $zone = $this->request->get('zone', 'alphanum');
        $code = $this->request->get('code', 'alphanum');

        // 不存在
        $card = $this->cardModel->getItem($code);
        if (!$card) {
            $this->response->setJsonContent(['code' => 2, 'msg' => 'card isn`t exist'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 已使用
        if ($card['status'] != 1) {
            $this->response->setJsonContent(['code' => 3, 'msg' => 'card is used'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 已过期
        if ($card['expired_in'] < date('Y-m-d H:i:s')) {
            $this->response->setJsonContent(['code' => 4, 'msg' => 'card is expired'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }
        //未开始
        if ($card['start_time'] > date('Y-m-d H:i:s')) {
            $this->response->setJsonContent(['code' => 6, 'msg' => 'card not started'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        // 使用限制
        if ($card['limit_times']) {
            // 按账号检查
            $times = $this->cardModel->checkTimesByAccountId($card['topic_id'], $account_id);
            if ($times >= $card['limit_times']) {
                $this->response->setJsonContent(['code' => 5, 'msg' => 'used times limit'],
                    JSON_UNESCAPED_UNICODE)->send();
                exit();
            }
        }

        // 区分code使用类型
        $code_limit_times = $card['code_limit_times'];

        if ($code_limit_times > 0) {  // 限制使用次数
            // 通过code检查使用次数
            $code_times = $this->cardModel->checkCodeTimesByCode($card['code']);
            if ($code_times >= $code_limit_times) {
                $this->response->setJsonContent(['code' => 5, 'msg' => 'used times limit'],
                    JSON_UNESCAPED_UNICODE)->send();
                exit();
            }
        }

        switch ($card['type']) {
            case 'cash';
                $data = [
                    'zone' => $zone,
                    'user_id' => $role_id,
                    'amount' => $card['data'],
                    'msg' => $card['title']
                ];
                $result = Utils::rpc('/prop/coin', $data);
                break;

            case 'discount';
                $result = false;
                break;

            case 'register';
                $gift_id = '';
                //$result = true;
                break;

            case 'prop';
                $data = [
                    'zone' => $zone,
                    'user_id' => $role_id,
                    'attach' => $card['data'],
                    'msg' => $card['title']
                ];
                $gift_id = $card['data'];
                $result = Utils::rpc('/prop/attach', $data);
                break;
        }

        /*
        if (!$result) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'failed'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }
        */

        $this->cardModel->cardLogs("{$account_id}", $code, $card['topic_id'], $code_limit_times);
        $this->response->setJsonContent(['code' => 0, 'msg' => 'success', 'gift_id' => $gift_id], JSON_UNESCAPED_UNICODE)->send();
        exit();
    }

}