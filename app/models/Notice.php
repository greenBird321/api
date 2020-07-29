<?php

namespace MyApp\Models;


use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Notice extends Model
{

    public function getLists($zone = '', $channel = '')
    {
        $dateTime = date('Y-m-d H:i:s');
        $sql = "SELECT id,title,content,img FROM notice
WHERE status=:status
AND ('$dateTime' BETWEEN start_time AND end_time)
AND (zone='' OR zone=:zone)
AND (channel='' OR channel=:channel)
ORDER BY sort";
        $bind = array('status' => 1, 'zone' => $zone, 'channel' => $channel);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetchAll();
    }


    public function getItem($id)
    {
        $sql = "SELECT id,title,content,img FROM notice WHERE status=:status AND id=:id";
        $bind = array('status' => 1, 'id' => $id);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch();
    }
}