<?php
/**
 * Created by PhpStorm.
 * User: lihe
 * Date: 2019/5/5
 * Time: 12:16 PM
 */

namespace MyApp\Controllers;

use MyApp\Models\Bug;

class GamebugController extends ControllerBase
{
    public $gamebugModel;

    public function initialize()
    {
        parent::initialize();
        $this->gamebugModel = new Bug();
    }


    public function indexAction()
    {
        if ($_POST) {
            $this->gamebugModel->account_id = $this->request->get('account_id');
            $this->gamebugModel->role_id = $this->request->get('role_id');
            $this->gamebugModel->device = $this->request->get('device', ['string', 'trim']);
            $this->gamebugModel->debug_info = $this->request->get('info', ['string', 'trim']);
            $this->gamebugModel->record_time = date('Y-m-d H:i:s', $this->request->get('record_time', 'int'));
            $response = $this->gamebugModel->save();
            if (!$response) {
                $this->response->setJsonContent(['code' => 1, 'msg' => 'save failed'], JSON_UNESCAPED_UNICODE)->send();
                exit;
            }
            $this->response->setJsonContent(['code' => 0, 'msg' => 'save success'], JSON_UNESCAPED_UNICODE)->send();
        }
    }


}