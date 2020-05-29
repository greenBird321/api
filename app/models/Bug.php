<?php
/**
 * Created by PhpStorm.
 * User: lihe
 * Date: 2019/5/5
 * Time: 12:18 PM
 */

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;
class Bug extends Model {

    public function initialize()
    {
        $this->setConnectionService('dbData');
        $this->setSource("game_debug");
    }
}