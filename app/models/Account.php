<?php

namespace MyApp\Models;


use Firebase\JWT\JWT;
use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Account extends Model
{

    static public function verifyAccessToken($access_token = '')
    {
        $key = DI::getDefault()->get('config')->setting->secret_key;
        try {
            JWT::$leeway = 300; // 允许误差秒数
            $decoded = JWT::decode($access_token, $key, array('HS256'));
            return [
                'account_id'   => $decoded->open_id,
                'account_name' => $decoded->name,
                'gender'       => $decoded->gender,
                'photo'        => $decoded->photo,
            ];
        } catch (Exception $e) {
            return false;
        }
    }

}