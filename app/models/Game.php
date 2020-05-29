<?php
/**
 * Created by PhpStorm.
 * User: lihe
 * Date: 2019/8/19
 * Time: 10:10 AM
 */

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Game extends Model
{

    // 增加关卡统计灌入方法
    public function addData($accountId, $roleId, $deviceId, $isPass, $checkpoint,$levelId)
    {
        $cTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO `users_level` (account_id, role_id, device_id, level_id, is_pass, checkpoint, arrive_time) VALUES ('{$accountId}', '{$roleId}', '{$deviceId}', '{$levelId}', $isPass, '{$checkpoint}','{$cTime}')";
        DI::getDefault()->get('dbData')->execute($sql);
    }

    // 增加时长统计的灌入方法
    public function addRoleTimes($accountId, $roleId, $deviceId, $times)
    {
        $sql = "INSERT INTO `users_times` (account_id, role_id, device_id, online_times) VALUES ('{$accountId}', '{$roleId}', '{$deviceId}', {$times})";
        DI::getDefault()->get("dbData")->execute($sql);
    }
}