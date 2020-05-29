<?php


namespace MyApp\Controllers;


use MyApp\Models\Activity;
use Phalcon\Mvc\Dispatcher;

class ActivityController extends ControllerBase
{


    private $activityModel;


    public function initialize()
    {
        parent::initialize();
        $this->activityModel = new Activity();
    }


    /**
     * 活动列表
     * /activity/lists?zone=3001&channel=facebook
     *
     * 返回:
     *    {
     *        "code": 0,
     *        "msg": "success",
     *        "data": [
     *            {
     *                "id": "2",
     *                "type": "prepay",
     *                "title": "预付费活动",
     *                "content": "",
     *                "url": "",
     *                "img": "",
     *                "img_small": "",
     *                "custom": "",
     *                "create_time": "2017-06-01 00:00:00",
     *                "end_time": "2017-06-09 00:00:00"
     *            },
     *            {
     *                "id": "1",
     *                "type": "spend",
     *                "title": "消费活动",
     *                "content": "",
     *                "url": "",
     *                "img": "",
     *                "img_small": "",
     *                "custom": "",
     *                "create_time": "2017-06-01 00:00:00",
     *                "end_time": "2017-06-09 00:00:00"
     *            }
     *        ]
     *    }
     */
    public function listsAction()
    {
        $zone = $this->request->get('zone', 'alphanum');
        $channel = $this->request->get('channel', 'alphanum');
        $result = $this->activityModel->getLists($zone, $channel);
        if (!$result) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'no data'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        $this->response->setJsonContent(
            ['code' => 0, 'msg' => 'success', 'data' => $result],
            JSON_UNESCAPED_UNICODE
        )->send();
        exit();
    }


    /**
     * TODO :: 完成 & 剩余
     * 活动详细
     * /activity/item?id=123456
     *
     * 返回:
     *    {
     *        "code": 0,
     *        "msg": "success",
     *        "data": {
     *            "id": "2",
     *            "type": "prepay",
     *            "title": "预付费活动",
     *            "content": "",
     *            "url": "",
     *            "img": "",
     *            "img_small": "",
     *            "custom": ""
     *            "create_time": "2017-06-01 00:00:00",
     *            "end_time": "2017-06-09 00:00:00"
     *        }
     *    }
     */
    public function itemAction()
    {
        $id = $this->request->get('id', 'int');

        $result = $this->activityModel->getItem($id);
        if (!$result) {
            $this->response->setJsonContent(['code' => 1, 'msg' => 'no data'], JSON_UNESCAPED_UNICODE)->send();
            exit();
        }

        $this->response->setJsonContent(
            ['code' => 0, 'msg' => 'success', 'data' => $result],
            JSON_UNESCAPED_UNICODE
        )->send();
        exit();
    }

}