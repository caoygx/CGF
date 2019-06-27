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
    'openid' => 
    array (
      'name' => 'openid',
      'type' => 'text',
      'size' => 30,
      'zh' => 'openid',
      'showPage' => '1111',
      'rawOption' => NULL,
    ),
    'credits' => 
    array (
      'name' => 'credits',
      'type' => 'text',
      'size' => 10,
      'zh' => '本次兑换扣除的积分',
      'rawOption' => NULL,
    ),
    'itemCode' => 
    array (
      'name' => 'itemCode',
      'type' => 'text',
      'size' => 30,
      'zh' => '自有商品商品编码(非必须字段)',
      'rawOption' => NULL,
    ),
    'timestamp' => 
    array (
      'name' => 'timestamp',
      'type' => '01',
      'size' => 30,
      'zh' => '1970',
      'tips' => '01开始的时间戳，毫秒为单位。',
      'rawOption' => NULL,
    ),
    'description' => 
    array (
      'name' => 'description',
      'type' => '8进行url解码)',
      'size' => 30,
      'zh' => '本次积分消耗的描述(带中文，请用utf',
      'rawOption' => NULL,
    ),
    'orderNum' => 
    array (
      'name' => 'orderNum',
      'type' => 'text',
      'size' => 30,
      'zh' => '兑吧订单号(请记录到数据库中)',
      'rawOption' => NULL,
    ),
    'type' => 
    array (
      'name' => 'type',
      'type' => 'text',
      'size' => 30,
      'zh' => 'record_type=0:兑换类型：alipay(支付宝), qb(Q币), coupon(优惠券), object(实物), phonebill(话费), phoneflow(流量), virtual(虚拟商品),game(游戏), hdtool(活动抽奖),sign(签到)所有类型不区分大小写
record_type=1:game(游戏), sign(签到), reSign(补签)。 hdtool(加积分活动)所有类型不区分大小写',
      'rawOption' => NULL,
    ),
    'facePrice' => 
    array (
      'name' => 'facePrice',
      'type' => 'text',
      'size' => 10,
      'zh' => '兑换商品的市场价值，单位是分，请自行转换单位',
      'rawOption' => NULL,
    ),
    'actualPrice' => 
    array (
      'name' => 'actualPrice',
      'type' => 'text',
      'size' => 10,
      'zh' => '此次兑换实际扣除开发者账户费用，单位为分',
      'rawOption' => NULL,
    ),
    'ip' => 
    array (
      'name' => 'ip',
      'type' => 'text',
      'size' => 30,
      'zh' => '用户ip',
      'rawOption' => NULL,
    ),
    'waitAudit' => 
    array (
      'name' => 'waitAudit',
      'type' => 'text',
      'size' => 10,
      'zh' => '是否需要审核(如需在自身系统进行审核处理，请记录下此信息)',
      'rawOption' => NULL,
    ),
    'params' => 
    array (
      'name' => 'params',
      'type' => '8进行解码) 实物商品：返回收货信息(姓名:手机号:省份:城市:区域:详细地址)、支付宝：返回账号信息(支付宝账号:实名)、话费：返回手机号、QB：返回QQ号',
      'size' => 30,
      'zh' => '详情参数，不同的类型，返回不同的内容，中间用英文冒号分隔。(支付宝类型带中文，请用utf',
      'rawOption' => NULL,
    ),
    'create_t' => 
    array (
      'name' => 'create_t',
      'type' => 'text',
      'size' => 10,
      'zh' => '创建时间',
      'rawOption' => NULL,
    ),
    'notice_timestamp' => 
    array (
      'name' => 'notice_timestamp',
      'type' => 'text',
      'size' => 30,
      'zh' => '处理结果时间戳',
      'rawOption' => NULL,
    ),
    'status' => 
    array (
      'name' => 'status',
      'type' => 'text',
      'size' => 10,
      'zh' => '处理结果',
      'rawOption' => NULL,
    ),
    'errorMessage' => 
    array (
      'name' => 'errorMessage',
      'type' => 'text',
      'size' => 30,
      'zh' => '出错原因',
      'rawOption' => NULL,
    ),
    'record_type' => 
    array (
      'name' => 'record_type',
      'type' => '>减积分  1',
      'size' => 10,
      'zh' => '0',
      'tips' => '> 加积分',
      'rawOption' => NULL,
    ),
    'account' => 
    array (
      'name' => 'account',
      'type' => 'text',
      'size' => 30,
      'zh' => '用户兑换虚拟商品时输入的账号，只有在打开虚拟商品账号输入开关时，会传输此参数。',
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
    'credits' => 
    array (
    ),
    'itemCode' => 
    array (
    ),
    'timestamp' => 
    array (
    ),
    'description' => 
    array (
    ),
    'orderNum' => 
    array (
    ),
    'type' => 
    array (
    ),
    'facePrice' => 
    array (
    ),
    'actualPrice' => 
    array (
    ),
    'ip' => 
    array (
    ),
    'waitAudit' => 
    array (
    ),
    'params' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'notice_timestamp' => 
    array (
    ),
    'status' => 
    array (
    ),
    'errorMessage' => 
    array (
    ),
    'record_type' => 
    array (
    ),
    'account' => 
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
    'credits' => 
    array (
    ),
    'itemCode' => 
    array (
    ),
    'timestamp' => 
    array (
    ),
    'description' => 
    array (
    ),
    'orderNum' => 
    array (
    ),
    'type' => 
    array (
    ),
    'facePrice' => 
    array (
    ),
    'actualPrice' => 
    array (
    ),
    'ip' => 
    array (
    ),
    'waitAudit' => 
    array (
    ),
    'params' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'notice_timestamp' => 
    array (
    ),
    'status' => 
    array (
    ),
    'errorMessage' => 
    array (
    ),
    'record_type' => 
    array (
    ),
    'account' => 
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
    'credits' => 
    array (
    ),
    'itemCode' => 
    array (
    ),
    'timestamp' => 
    array (
    ),
    'description' => 
    array (
    ),
    'orderNum' => 
    array (
    ),
    'type' => 
    array (
    ),
    'facePrice' => 
    array (
    ),
    'actualPrice' => 
    array (
    ),
    'ip' => 
    array (
    ),
    'waitAudit' => 
    array (
    ),
    'params' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'notice_timestamp' => 
    array (
    ),
    'status' => 
    array (
    ),
    'errorMessage' => 
    array (
    ),
    'record_type' => 
    array (
    ),
    'account' => 
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
    'credits' => 
    array (
    ),
    'itemCode' => 
    array (
    ),
    'timestamp' => 
    array (
    ),
    'description' => 
    array (
    ),
    'orderNum' => 
    array (
    ),
    'type' => 
    array (
    ),
    'facePrice' => 
    array (
    ),
    'actualPrice' => 
    array (
    ),
    'ip' => 
    array (
    ),
    'waitAudit' => 
    array (
    ),
    'params' => 
    array (
    ),
    'create_t' => 
    array (
    ),
    'notice_timestamp' => 
    array (
    ),
    'status' => 
    array (
    ),
    'errorMessage' => 
    array (
    ),
    'record_type' => 
    array (
    ),
    'account' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '',
    'name' => 'pm_duiba_credits_record',
  ),
);