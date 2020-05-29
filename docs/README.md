## 一.公告
#### 1.1 公告列表

## 二.活动
#### 2.1 活动列表 /activity/lists

请求参数 | 类型 | 必选 | 描述
--- | --- |:---:| ---
zone    | varchar(16)  | 是 | 区服ID, 默认空字符串
channel | varchar(32)  | 是 | 渠道, 默认空字符串

返回参考：

返回参数 | 描述
--- | ---
id      | 活动ID
type    | 活动类型
title   | 标题
content | 内容
url     | URL
img     | 图片地址
img_small| 图片地址 小图
custom  | 自定义, 例: 游戏特殊锚点
create_time | 开始时间
end_time    | 结束时间

#### 2.2 活动详情 /activity/item?id=:id
返回参考 2.1

## 三. 礼品卡接口
#### 3.1 礼品卡验证接口 /card/verify

请求参数 | 类型 | 必选 | 描述
--- | --- |:---:| ---
zone        | varchar(16)  | 是 | 区服ID
user_id     | varchar(32)  | 是 | 用户ID （角色ID）
access_token| varchar(256) | 是 | 授权Token (登录返回)
code        | varchar(32)  | 是 | 礼品卡ID

成功返回：
```json
{"code":0,"msg":"success"}
```

失败返回：
```json
{"code":1,"msg":"card error"}
```

code值定义：
1 :  输入的字符串<6字符 格式错误
2 :  卡号不存在
3 :  卡号已经使用
4 :  卡号已经过期
5 :  使用限制

## 四. 邀请码
#### 4.1 获取当前用户邀请码 /invite/me

请求参数 | 类型 | 必选 | 描述
--- | --- |:---:| ---
zone        | varchar(16)  | 是 | 区服ID
user_id     | varchar(32)  | 是 | 用户ID （角色ID）
access_token| varchar(256) | 是 | 授权Token (登录返回)

成功返回：
```json
{"code":0,"msg":"success","data":{"code":"sd6h2c82"}}
```

## 五. 产品
#### 5.1  产品接口 /product

请求参数：  

参数名 | 类型 | 必选 | 描述 
--- | --- |:---:| ---
gateway     | varchar(16)  | 是 | 支付网关, 例: alipay、weixin、apple、google 其他国内渠道传others
zone        | varchar(16)  | 是 | 区服ID
user_id     | varchar(32)  | 是 | 用户ID （角色ID）
custom      | varchar(32)  | 是 | channel信息

失败返回
```json
{
    "code": 1,
    "msg": "no products"
}
```

成功返回 
```json
{
    "code": 0,
    "msg": "success",
    "data": [
        {
            "name": "100钻石",
            "product_id": "xt.diamond.6",
            "price": "6.00",
            "currency": "CNY",
            "coin": "100",
            "remark": "6元100钻石",
            "image": "http://www.example.com/logo.jpg",
            "custom": "card_month"
        },
        {
            "name": "200钻石",
            "product_id": "xt.diamond.12",
            "price": "12.00",
            "currency": "CNY",
            "coin": "200",
            "remark": "12元200钻石",
            "image": "http://www.example.com/logo.jpg",
            "custom": "",
            "promo": {
                "lowest": "0",
                "coin": "10",
                "prop": "2021"
            },
            "first_purchase": {
                "lowest": "0",
                "coin": "20",
                "prop": "2022"
            }
        }
    ]
}
```
字段custom | 描述
--- | ---
card_month  | 月卡
vip         | 会员卡

补充 | 说明
--- | ---
promo           | 促销赠送
first_purchase  | 首充赠送
