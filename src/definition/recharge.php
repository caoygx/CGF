<?php 
 return array (
  'base' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'hidden',
      'size' => 10,
      'zh' => 'id',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'openid' => 
    array (
      'name' => 'openid',
      'type' => 'text',
      'size' => 30,
      'zh' => 'openid',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'recharge_id' => 
    array (
      'name' => 'recharge_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '充值id',
      'rawOption' => NULL,
    ),
    'type' => 
    array (
      'name' => 'type',
      'type' => 'select',
      'size' => 10,
      'zh' => '类型',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '充值',
        1 => '支付',
        2 => '返还多余拍币',
        3 => '竞拍未中返现',
        4 => '赠送',
        5 => '打赏(扣)',
        6 => '打赏(加)',
      ),
      'rawOption' => '0:充值,1:支付,2:返还多余拍币, 3:竞拍未中返现,4:赠送,5:打赏(扣),6:打赏(加)',
      'show_text' => 'type_text',
    ),
    'money' => 
    array (
      'name' => 'money',
      'type' => 'text',
      'size' => 10,
      'zh' => '金额',
      'rawOption' => NULL,
    ),
    'cost' => 
    array (
      'name' => 'cost',
      'type' => 'text',
      'size' => 10,
      'zh' => '金额',
      'rawOption' => NULL,
    ),
    'status' => 
    array (
      'name' => 'status',
      'type' => 'select',
      'size' => 10,
      'zh' => '充值状态',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '默认',
        1 => '交易
中',
        2 => '交
易成功',
        3 => '交易失败',
      ),
      'rawOption' => '0:默认,
1:交易
中, 2:交
易成功,
3:交易失败',
      'show_text' => 'status_text',
    ),
    'source' => 
    array (
      'name' => 'source',
      'type' => 'select',
      'size' => 10,
      'zh' => '充值来源',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '微信',
        1 => '支付宝',
        2 => '余额',
        3 => '易宝',
      ),
      'rawOption' => '0:微信,
1:支付宝,
2:余额,3:
易宝',
      'show_text' => 'source_text',
    ),
    'plat_order_id' => 
    array (
      'name' => 'plat_order_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '三方平台支付单号',
      'rawOption' => NULL,
    ),
    'pay_order_id' => 
    array (
      'name' => 'pay_order_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '支付单号',
      'rawOption' => NULL,
    ),
    'goods_id' => 
    array (
      'name' => 'goods_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '竞拍商品id',
      'rawOption' => NULL,
    ),
    'order_id' => 
    array (
      'name' => 'order_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '订单号',
      'rawOption' => NULL,
    ),
    'create_t' => 
    array (
      'name' => 'create_t',
      'type' => 'text',
      'size' => 10,
      'zh' => 'create_t',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'modify_t' => 
    array (
      'name' => 'modify_t',
      'type' => 'text',
      'size' => 10,
      'zh' => 'modify_t',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'msg' => 
    array (
      'name' => 'msg',
      'type' => 'text',
      'size' => 30,
      'zh' => '描述',
      'rawOption' => NULL,
    ),
    'payway' => 
    array (
      'name' => 'payway',
      'type' => 'text',
      'size' => 10,
      'zh' => 'payway',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'ch' => 
    array (
      'name' => 'ch',
      'type' => 'text',
      'size' => 30,
      'zh' => '渠道',
      'rawOption' => NULL,
    ),
    'wx_status' => 
    array (
      'name' => 'wx_status',
      'type' => 'text',
      'size' => 10,
      'zh' => '微信是否被调起',
      'rawOption' => NULL,
    ),
    'order_check_status' => 
    array (
      'name' => 'order_check_status',
      'type' => 'text',
      'size' => 10,
      'zh' => '订单检查状态',
      'rawOption' => NULL,
    ),
  ),
  'add' => 
  array (
    'id' => 
    array (
    ),
    'openid' => 
    array (
    ),
    'recharge_id' => 
    array (
    ),
    'type' => 
    array (
    ),
    'money' => 
    array (
    ),
    'cost' => 
    array (
    ),
    'status' => 
    array (
    ),
    'source' => 
    array (
    ),
    'plat_order_id' => 
    array (
    ),
    'pay_order_id' => 
    array (
    ),
    'goods_id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'modify_t' => 
    array (
    ),
    'msg' => 
    array (
    ),
    'payway' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'wx_status' => 
    array (
    ),
    'order_check_status' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'id' => 
    array (
    ),
    'openid' => 
    array (
    ),
    'recharge_id' => 
    array (
    ),
    'type' => 
    array (
    ),
    'money' => 
    array (
    ),
    'cost' => 
    array (
    ),
    'status' => 
    array (
    ),
    'source' => 
    array (
    ),
    'plat_order_id' => 
    array (
    ),
    'pay_order_id' => 
    array (
    ),
    'goods_id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'modify_t' => 
    array (
    ),
    'msg' => 
    array (
    ),
    'payway' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'wx_status' => 
    array (
    ),
    'order_check_status' => 
    array (
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'openid' => 
    array (
    ),
    'recharge_id' => 
    array (
    ),
    'type' => 
    array (
    ),
    'money' => 
    array (
    ),
    'cost' => 
    array (
    ),
    'status' => 
    array (
    ),
    'source' => 
    array (
    ),
    'plat_order_id' => 
    array (
    ),
    'pay_order_id' => 
    array (
    ),
    'goods_id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'modify_t' => 
    array (
    ),
    'msg' => 
    array (
    ),
    'payway' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'wx_status' => 
    array (
    ),
    'order_check_status' => 
    array (
    ),
  ),
  'search' => 
  array (
    'id' => 
    array (
    ),
    'openid' => 
    array (
    ),
    'recharge_id' => 
    array (
    ),
    'type' => 
    array (
    ),
    'money' => 
    array (
    ),
    'cost' => 
    array (
    ),
    'status' => 
    array (
    ),
    'source' => 
    array (
    ),
    'plat_order_id' => 
    array (
    ),
    'pay_order_id' => 
    array (
    ),
    'goods_id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'modify_t' => 
    array (
    ),
    'msg' => 
    array (
    ),
    'payway' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'wx_status' => 
    array (
    ),
    'order_check_status' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '充值记录',
    'property' => '',
    'pageButton' => 
    array (
    ),
    'name' => 'pm_recharge',
  ),
);