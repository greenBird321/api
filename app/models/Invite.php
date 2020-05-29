<?php

namespace MyApp\Models;


use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Invite extends Model
{

    public function getMyCode($zone = '', $user_id = 0)
    {
        $sql = "SELECT code FROM invite_code WHERE user_id=:user_id";
        $bind = array('user_id' => $zone . '-' . $user_id);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        return $query->fetch();
    }


    public function setMyCode($zone = '', $user_id = 0, $code = '')
    {
        $user_id = $zone . '-' . $user_id;
        $sql = "INSERT INTO `invite_code`(code,user_id) VALUES ('$code','$user_id')";
        return DI::getDefault()->get('dbData')->execute($sql);
    }

}