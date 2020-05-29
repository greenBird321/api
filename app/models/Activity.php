<?php

namespace MyApp\Models;


use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Activity extends Model
{

    public function getLists($zone = '', $channel = '')
    {
        $tz_output = $this->di->get('config')->_tz_out;
        $utc = '+00:00';

        $dateTime = date('Y-m-d H:i:s');
        $sql = "SELECT id,`type`,title,content,url,img,img_small,custom,
CONVERT_TZ(start_time, '$utc', '$tz_output') start_time, CONVERT_TZ(end_time, '$utc', '$tz_output') end_time
FROM activity
WHERE status=:status AND visible=1
AND ('$dateTime' BETWEEN start_time AND end_time)
AND (zone='' OR zone=:zone)
AND (channel='' OR channel=:channel)
ORDER BY sort DESC, id DESC";
        $bind = array('status' => 1, 'zone' => $zone, 'channel' => $channel);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetchAll();
    }


    public function getItem($id)
    {
        $sql = "SELECT id,`type`,title,content,url,img,img_small,custom,start_time,end_time FROM activity WHERE status=:status AND id=:id";
        $bind = array('status' => 1, 'id' => $id);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch();
    }

}