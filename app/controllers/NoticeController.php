<?php


namespace MyApp\Controllers;


use MyApp\Models\Notice;
use Phalcon\Mvc\Dispatcher;

class NoticeController extends ControllerBase
{


    private $noticeModel;


    public function initialize()
    {
        parent::initialize();
        $this->noticeModel = new Notice();
    }


    /**
     * 公告列表
     * /notice/lists?zone=3001&channel=facebook
     */
    public function listsAction()
    {
        $zone = $this->request->get('zone', 'alphanum');
        $channel = $this->request->get('channel', 'alphanum');
        $result = $this->noticeModel->getLists($zone, $channel);
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
     * 公告详细
     * /notice/item?id=123456
     */
    public function itemAction()
    {
        $id = $this->request->get('id', 'int');

        $result = $this->noticeModel->getItem($id);
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