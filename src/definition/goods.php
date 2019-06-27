<?php 
 return array (
  'base' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'hidden',
      'size' => 10,
      'zh' => 'ID',
      'rawOption' => NULL,
    ),
    'name' => 
    array (
      'name' => 'name',
      'type' => 'text',
      'size' => 30,
      'zh' => '商品名',
      'rawOption' => NULL,
    ),
    'memo' => 
    array (
      'name' => 'memo',
      'type' => 'editor',
      'row' => 10,
      'zh' => '备注',
      'showPage' => '1100',
      'rawOption' => NULL,
    ),
    'init_price' => 
    array (
      'name' => 'init_price',
      'type' => 'text',
      'size' => 10,
      'zh' => '竞拍初始价',
      'rawOption' => NULL,
    ),
    'price' => 
    array (
      'name' => 'price',
      'type' => 'text',
      'size' => 10,
      'zh' => '市场价',
      'rawOption' => NULL,
    ),
    'state' => 
    array (
      'name' => 'state',
      'type' => 'select',
      'size' => 10,
      'zh' => '状态',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '上架',
        1 => '下架',
      ),
      'rawOption' => '0:上架,1:下架',
      'show_text' => 'state_text',
    ),
    'sort' => 
    array (
      'name' => 'sort',
      'type' => 'text',
      'size' => 10,
      'zh' => '商品排序',
      'rawOption' => NULL,
    ),
    'countdown' => 
    array (
      'name' => 'countdown',
      'type' => 'text',
      'size' => 10,
      'zh' => '倒计时',
      'rawOption' => NULL,
    ),
    'shelf' => 
    array (
      'name' => 'shelf',
      'type' => 'select',
      'size' => 10,
      'zh' => '是否自动上架',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '否',
        1 => '是',
      ),
      'rawOption' => '0:否,1:是',
      'show_text' => 'shelf_text',
    ),
    'robot_id' => 
    array (
      'name' => 'robot_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '机器人id',
      'rawOption' => NULL,
    ),
    'create_t' => 
    array (
      'name' => 'create_t',
      'type' => 'text',
      'size' => 10,
      'zh' => '创建时间',
      'showPage' => '0',
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
      'showPage' => '2',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'function' => 'date("y-m-d h:i:s",###)',
    ),
    'user_id' => 
    array (
      'name' => 'user_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '用户id',
      'rawOption' => NULL,
    ),
    'pur_price' => 
    array (
      'name' => 'pur_price',
      'type' => 'text',
      'size' => 10,
      'zh' => '成本',
      'rawOption' => NULL,
    ),
    'init_param_index' => 
    array (
      'name' => 'init_param_index',
      'type' => 'text',
      'size' => 10,
      'zh' => '初始参数',
      'rawOption' => NULL,
    ),
    'disabled_channel' => 
    array (
      'name' => 'disabled_channel',
      'type' => 'text',
      'size' => 30,
      'zh' => '不显示的渠道',
      'rawOption' => NULL,
    ),
    'weight' => 
    array (
      'name' => 'weight',
      'type' => 'text',
      'size' => 10,
      'zh' => '重量',
      'rawOption' => NULL,
    ),
    'if_pricediff' => 
    array (
      'name' => 'if_pricediff',
      'type' => 'text',
      'size' => 10,
      'zh' => '是否差价购',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        0 => '否',
        1 => '是',
      ),
      'rawOption' => '0:否,1:是',
      'show_text' => 'if_pricediff_text',
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
        0 => '普通商品',
        1 => '会员充值',
        2 => '话费充值',
      ),
      'rawOption' => '0:普通商品,1:会员充值,2:话费充值',
      'show_text' => 'type_text',
    ),
    'card_price' => 
    array (
      'name' => 'card_price',
      'type' => 'text',
      'size' => 10,
      'zh' => '卡价',
      'rawOption' => NULL,
    ),
    'discount_rate' => 
    array (
      'name' => 'discount_rate',
      'type' => 'text',
      'size' => 10,
      'zh' => '折扣率',
      'rawOption' => NULL,
    ),
  ),
  'add' => 
  array (
    'id' => 
    array (
    ),
    'name' => 
    array (
    ),
    'memo' => 
    array (
    ),
    'init_price' => 
    array (
    ),
    'price' => 
    array (
    ),
    'state' => 
    array (
    ),
    'sort' => 
    array (
    ),
    'countdown' => 
    array (
    ),
    'shelf' => 
    array (
    ),
    'robot_id' => 
    array (
    ),
    'user_id' => 
    array (
    ),
    'pur_price' => 
    array (
    ),
    'init_param_index' => 
    array (
    ),
    'disabled_channel' => 
    array (
    ),
    'weight' => 
    array (
    ),
    'if_pricediff' => 
    array (
    ),
    'type' => 
    array (
    ),
    'card_price' => 
    array (
    ),
    'discount_rate' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'id' => 
    array (
    ),
    'name' => 
    array (
    ),
    'memo' => 
    array (
    ),
    'init_price' => 
    array (
    ),
    'price' => 
    array (
    ),
    'state' => 
    array (
    ),
    'sort' => 
    array (
    ),
    'countdown' => 
    array (
    ),
    'shelf' => 
    array (
    ),
    'robot_id' => 
    array (
    ),
    'user_id' => 
    array (
    ),
    'pur_price' => 
    array (
    ),
    'init_param_index' => 
    array (
    ),
    'disabled_channel' => 
    array (
    ),
    'weight' => 
    array (
    ),
    'if_pricediff' => 
    array (
    ),
    'type' => 
    array (
    ),
    'card_price' => 
    array (
    ),
    'discount_rate' => 
    array (
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'name' => 
    array (
    ),
    'init_price' => 
    array (
    ),
    'price' => 
    array (
    ),
    'state' => 
    array (
    ),
    'sort' => 
    array (
    ),
    'countdown' => 
    array (
    ),
    'shelf' => 
    array (
    ),
    'robot_id' => 
    array (
    ),
    'modify_t' => 
    array (
    ),
    'user_id' => 
    array (
    ),
    'pur_price' => 
    array (
    ),
    'init_param_index' => 
    array (
    ),
    'disabled_channel' => 
    array (
    ),
    'weight' => 
    array (
    ),
    'if_pricediff' => 
    array (
    ),
    'type' => 
    array (
    ),
    'card_price' => 
    array (
    ),
    'discount_rate' => 
    array (
    ),
  ),
  'search' => 
  array (
    'id' => 
    array (
    ),
    'name' => 
    array (
    ),
    'init_price' => 
    array (
    ),
    'price' => 
    array (
    ),
    'state' => 
    array (
    ),
    'sort' => 
    array (
    ),
    'countdown' => 
    array (
    ),
    'shelf' => 
    array (
    ),
    'robot_id' => 
    array (
    ),
    'user_id' => 
    array (
    ),
    'pur_price' => 
    array (
    ),
    'init_param_index' => 
    array (
    ),
    'disabled_channel' => 
    array (
    ),
    'weight' => 
    array (
    ),
    'if_pricediff' => 
    array (
    ),
    'type' => 
    array (
    ),
    'card_price' => 
    array (
    ),
    'discount_rate' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '商品表',
    'property' => '',
    'action' => 'edit:编辑:id,del:删除:id',
    'sort' => 
    array (
      0 => 'create_time',
      1 => 'desc',
    ),
    'pageButton' => 
    array (
      0 => 'export',
      1 => 'showMenu',
    ),
    'function' => NULL,
    'name' => 'pm_goods',
  ),
);