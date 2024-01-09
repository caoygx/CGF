<?php 
 return array (
  'base' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'text',
      'size' => 10,
      'zh' => '编号',
    ),
    'order_no' => 
    array (
      'name' => 'order_no',
      'type' => 'text',
      'size' => 30,
      'zh' => '订单号',
    ),
    'user_id' => 
    array (
      'name' => 'user_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '用户id',
    ),
    'goods_id' => 
    array (
      'name' => 'goods_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '商品id',
    ),
    'goods_title' => 
    array (
      'name' => 'goods_title',
      'type' => 'text',
      'size' => 30,
      'rawOption' => 'show_func=order_course_title',
      'options' => 
      array (
        'show_func=order_course_title' => '',
      ),
      'zh' => '产品名称',
      'show_text' => 'goods_title_text',
    ),
    'snapshot' => 
    array (
      'name' => 'snapshot',
      'type' => 'text',
      'size' => 30,
      'zh' => '产品快照',
    ),
    'status' => 
    array (
      'name' => 'status',
      'type' => 'select',
      'size' => 10,
      'rawOption' => '1:待支付,2:已支付,3:已完成,4:已取消,5:申请退款,6:支付失败,7:退款处理中,8:退款成功,9:退款失败,10:确认报价,11:确认订单',
      'options' => 
      array (
        1 => '待支付',
        2 => '已支付',
        3 => '已完成',
        4 => '已取消',
        5 => '申请退款',
        6 => '支付失败',
        7 => '退款处理中',
        8 => '退款成功',
        9 => '退款失败',
        10 => '确认报价',
        11 => '确认订单',
      ),
      'zh' => '订单状态',
      'show_text' => 'status_text',
    ),
    'price' => 
    array (
      'name' => 'price',
      'type' => 'text',
      'size' => 10,
      'zh' => '支付金额',
    ),
    'pay_price' => 
    array (
      'name' => 'pay_price',
      'type' => 'text',
      'size' => 10,
      'zh' => '实际支付价格',
    ),
    'coupon_id' => 
    array (
      'name' => 'coupon_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '优惠券id',
    ),
    'pay_order_id' => 
    array (
      'name' => 'pay_order_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '第三方支付单id',
    ),
    'paymethod' => 
    array (
      'name' => 'paymethod',
      'type' => 'text',
      'size' => 30,
      'rawOption' => 'alipay:支付宝,wxpay:微信支付',
      'options' => 
      array (
        'alipay' => '支付宝',
        'wxpay' => '微信支付',
      ),
      'zh' => '支付方式',
      'show_text' => 'paymethod_text',
    ),
    'channel' => 
    array (
      'name' => 'channel',
      'type' => 'text',
      'size' => 30,
      'rawOption' => 'show_func=get_pay',
      'options' => 
      array (
        'show_func=get_pay' => '',
      ),
      'zh' => '渠道',
      'show_text' => 'channel_text',
    ),
    'platform' => 
    array (
      'name' => 'platform',
      'type' => 'text',
      'size' => 30,
      'zh' => '平台',
    ),
    'create_time' => 
    array (
      'name' => 'create_time',
      'type' => 'time',
      'zh' => '创建时间',
    ),
    'update_time' => 
    array (
      'name' => 'update_time',
      'type' => 'time',
      'zh' => '更新时间',
    ),
    'finish_time' => 
    array (
      'name' => 'finish_time',
      'type' => 'time',
      'zh' => '订单结束时间',
    ),
    'fix_status' => 
    array (
      'name' => 'fix_status',
      'type' => 'text',
      'size' => 10,
      'zh' => '订单修复状态',
    ),
    'lock_version' => 
    array (
      'name' => 'lock_version',
      'type' => 'text',
      'size' => 30,
      'zh' => '版本锁',
    ),
    'callback_data' => 
    array (
      'name' => 'callback_data',
      'type' => 'textarea',
      'row' => 10,
      'zh' => '回调参数',
    ),
    'aid' => 
    array (
      'name' => 'aid',
      'type' => 'text',
      'size' => 30,
      'zh' => 'aid',
    ),
    'ad_cooperation_time' => 
    array (
      'name' => 'ad_cooperation_time',
      'type' => 'time',
      'zh' => '广告合作时间',
    ),
    'ad_start_time' => 
    array (
      'name' => 'ad_start_time',
      'type' => 'time',
      'zh' => '广告开始投放时间',
    ),
    'merchant_id' => 
    array (
      'name' => 'merchant_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '站点id',
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'order_no' => 
    array (
    ),
    'user_id' => 
    array (
    ),
    'goods_id' => 
    array (
    ),
    'goods_title' => 
    array (
    ),
    'status' => 
    array (
    ),
    'price' => 
    array (
    ),
    'pay_price' => 
    array (
    ),
    'coupon_id' => 
    array (
    ),
    'pay_order_id' => 
    array (
    ),
    'paymethod' => 
    array (
    ),
    'channel' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'aid' => 
    array (
    ),
    'ad_cooperation_time' => 
    array (
    ),
    'ad_start_time' => 
    array (
    ),
    'merchant_id' => 
    array (
    ),
  ),
  'search' => 
  array (
    'id' => 
    array (
    ),
    'order_no' => 
    array (
    ),
    'user_id' => 
    array (
    ),
    'goods_id' => 
    array (
    ),
    'goods_title' => 
    array (
    ),
    'status' => 
    array (
    ),
    'pay_price' => 
    array (
    ),
    'coupon_id' => 
    array (
    ),
    'pay_order_id' => 
    array (
    ),
    'paymethod' => 
    array (
    ),
    'channel' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'aid' => 
    array (
    ),
    'ad_cooperation_time' => 
    array (
    ),
    'ad_start_time' => 
    array (
    ),
    'merchant_id' => 
    array (
    ),
  ),
  'add' => 
  array (
    'goods_id' => 
    array (
    ),
    'goods_title' => 
    array (
    ),
    'status' => 
    array (
    ),
    'price' => 
    array (
    ),
    'pay_price' => 
    array (
    ),
    'coupon_id' => 
    array (
    ),
    'paymethod' => 
    array (
    ),
    'aid' => 
    array (
    ),
    'ad_cooperation_time' => 
    array (
    ),
    'ad_start_time' => 
    array (
    ),
    'merchant_id' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'goods_id' => 
    array (
    ),
    'status' => 
    array (
    ),
    'price' => 
    array (
    ),
    'pay_price' => 
    array (
    ),
    'coupon_id' => 
    array (
    ),
    'paymethod' => 
    array (
    ),
    'aid' => 
    array (
    ),
    'ad_cooperation_time' => 
    array (
    ),
    'ad_start_time' => 
    array (
    ),
    'merchant_id' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '订单表',
    'name' => 'order',
  ),
);