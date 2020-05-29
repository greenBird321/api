<?php

namespace MyApp\Models;


use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Card extends Model
{

    public function getItem($code = '')
    {
        $sql = "SELECT cdk.code,cdk.topic_id,cdk.status,topic.title,topic.type,topic.data,topic.limit_times,topic.code_limit_times,topic.expired_in,topic.start_time 
FROM `card_code` cdk, `card_topic` topic
WHERE cdk.code=:code AND cdk.topic_id=topic.id";
        $bind = array('code' => $code);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch();
    }


    public function checkTimes($item = 0, $user_id = 0)
    {
        $sql = "SELECT COUNT(1) times FROM logs_card WHERE item_id=:item AND user_id=:user_id";
        $bind = array('item' => $item, 'user_id' => $user_id);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch()['times'];
    }

    public function checkTimesByAccountId($item = 0, $account_id = 0)
    {
        $sql = "SELECT COUNT(1) times FROM logs_card WHERE item_id = :item AND user_id = :account_id";
        $bind = array('item' => $item, 'account_id' => $account_id);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch()['times'];
    }

    public function checkCodeTimesByCode($code = '')
    {
        $sql = "SELECT COUNT(1) codeTimes FROM logs_card WHERE code = :code";
        $bind = array('code' => $code);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch()['codeTimes'];
    }

    public function cardLogs($user_id = '', $code = '', $item_id = 0, $type = -1)
    {
        $dateTime = date('Y-m-d H:i:s');
        $sql = '';
        if ($type < 0) {
            $sql = "UPDATE `card_code` SET status=0 WHERE `code`='$code';";
        }
        $sql .= "INSERT INTO `logs_card`(code,user_id,item_id,create_time) VALUES ('$code','$user_id',$item_id,'$dateTime')";
        DI::getDefault()->get('dbData')->execute($sql);
    }

}