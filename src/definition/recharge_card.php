<?php 
 return array (
  'base' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'text',
      'size' => 10,
      'zh' => 'id',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'order_id' => 
    array (
      'name' => 'order_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '订单id',
      'rawOption' => NULL,
    ),
    'mobile' => 
    array (
      'name' => 'mobile',
      'type' => 'text',
      'size' => 30,
      'zh' => '手机号',
      'rawOption' => NULL,
    ),
    'client_order_id' => 
    array (
      'name' => 'client_order_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '客户订单',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'price' => 
    array (
      'name' => 'price',
      'type' => 'text',
      'size' => 10,
      'zh' => '价格',
      'rawOption' => NULL,
    ),
    'status' => 
    array (
      'name' => 'status',
      'type' => 'select',
      'size' => 10,
      'zh' => '状态',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '未充值',
        1 => '充值中',
        2 => '充值成功',
        3 => '充值失败',
      ),
      'rawOption' => '0:未充值,1:充值中,2:充值成功,3:充值失败',
      'show_text' => 'status_text',
    ),
    'result' => 
    array (
      'name' => 'result',
      'type' => 'textarea',
      'row' => 10,
      'zh' => '结果',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'create_t' => 
    array (
      'name' => 'create_t',
      'type' => 'datetimeRangePicker',
      'size' => 10,
      'zh' => '创建时间',
      'showPage' => '1111',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'function' => 'date("y-m-d h:i:s",###)',
    ),
    'modify_t' => 
    array (
      'name' => 'modify_t',
      'type' => 'text',
      'size' => 10,
      'zh' => '修改时间',
      'showPage' => '0',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'function' => 'date("y-m-d h:i:s",###)',
    ),
  ),
  'add' => 
  array (
    'id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'mobile' => 
    array (
    ),
    'price' => 
    array (
    ),
    'status' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'mobile' => 
    array (
    ),
    'price' => 
    array (
    ),
    'status' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'mobile' => 
    array (
    ),
    'price' => 
    array (
    ),
    'status' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'search' => 
  array (
    'id' => 
    array (
    ),
    'order_id' => 
    array (
    ),
    'mobile' => 
    array (
    ),
    'price' => 
    array (
    ),
    'status' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '手机充值',
    'property' => '',
    'action' => 'id:编辑:aa',
    'sort' => 
    array (
      0 => 'shownemu',
      1 => 'export',
    ),
    'name' => 'pm_recharge_card',
  ),
);