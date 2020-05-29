<?php


namespace MyApp\Controllers;


use MyApp\Models\Product;
use Phalcon\Mvc\Dispatcher;

class ProductController extends ControllerBase
{

    private $productModel;


    public function initialize()
    {
        parent::initialize();
        $this->productModel = new Product();
    }


    /**
     * 产品列表
     */
    public function indexAction()
    {
        $gateway = $this->request->get('gateway', 'alphanum');
        $zone = $this->request->get('zone', 'alphanum');
        $user_id = $this->request->get('user_id', 'int');
        // 获取渠道的产品
        $channel = $this->request->get('channel', 'alphanum');
        $data = $this->productModel->getProductListByUser($gateway, $channel, $zone, $user_id);
        if (!$data) {
            $this->response->setJsonContent(['code' => 1, 'msg' => _('no products')])->send();
            exit();
        }
        $this->response->setJsonContent(
            [
                'code' => 0,
                'msg'  => _('success'),
                'data' => $data
            ],
            JSON_UNESCAPED_UNICODE
        )->send();
        exit();
    }

}