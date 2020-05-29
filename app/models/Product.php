<?php

namespace MyApp\Models;


use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Product extends Model
{


    public function getProductListByUser($gateway = '', $channel = '', $zone, $user_id)
    {
        $dateTime = date('Y-m-d H:i:s');

        // 产品
        if($channel && in_array($channel,['special']))
        {
            $sql = "SELECT `name`,product_id,price,currency,coin,remark,image,custom FROM `products` WHERE gateway=:gateway AND status=1 AND channel='{$channel}' ORDER BY sort DESC";
        }else{
            $sql = "SELECT `name`,product_id,price,currency,coin,remark,image,custom FROM `products` WHERE gateway=:gateway AND status=1 AND channel='' ORDER BY sort DESC";
        }
        $bind = array('gateway' => $gateway);
        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $products = $query->fetchAll();
        if (!$products) {
            return false;
        }
        $products = array_column($products, null, 'product_id');

        // 首充检查
        $sql = "SELECT product_id FROM `logs_purchase` WHERE user_id='{$zone}-{$user_id}' GROUP BY product_id";
        $query = DI::getDefault()->get('dbData')->query($sql);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $hasPurchaseProducts = $query->fetchAll();
        if ($hasPurchaseProducts) {
            $hasPurchaseProducts = array_column($hasPurchaseProducts, 'product_id');
        }

        // 附属产品配置
        $sql = "SELECT product_id,type,lowest,coin,prop FROM `products_cfg` WHERE '$dateTime' BETWEEN start_time AND end_time";
        $query = DI::getDefault()->get('dbData')->query($sql);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $extra = $query->fetchAll();
        if ($extra) {
            foreach ($extra as $ext) {
                $productId = $ext['product_id'];
                $type = $ext['type'];
                if ($type == 'first_purchase' && in_array($productId, $hasPurchaseProducts)) {
                    continue;
                }
                if (isset($products[$productId])) {
                    $products[$productId][$type] = [
                        'lowest' => $ext['lowest'],
                        'coin'   => $ext['coin'],
                        'prop'   => $ext['prop'],
                    ];
                }
            }
        }

        return array_values($products);
    }


}