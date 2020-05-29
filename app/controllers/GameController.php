<?php
/**
 * Created by PhpStorm.
 * User: lihe
 * Date: 2019/8/19
 * Time: 10:05 AM
 */

namespace MyApp\Controllers;

use MyApp\Models\Game;

class GameController extends ControllerBase
{
    private $gameModel;

    public function initialize()
    {
        parent::initialize();
        $this->gameModel = new Game();
    }

    // todo url http://domin/game/status?account_id=1234&role_id=4444&device=555555&level_id=6-1
    // 查询语句 SELECT COUNT(DISTINCT account_id) as count, level_id FROM users_level GROUP BY level_id
    // 统计打点
    public function statusAction()
    {
        $accountId = $this->request->get("account_id");
        $roleId = $this->request->get("role_id");
        $deviceId = $this->request->get("device");
        $levelId = $this->request->get("level_id");
        $isPass = $this->request->get("is_pass");
        $checkpoint = $this->request->get("checkpoint");
        $this->gameModel->addData($accountId, $roleId, $deviceId,$isPass, $checkpoint,$levelId);
        $this->response->setJsonContent(
            [
                'code' => 0,
                'msg' => _('success'),
            ],
            JSON_UNESCAPED_UNICODE
        )->send();
        exit();
    }
}