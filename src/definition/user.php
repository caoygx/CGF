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
      'showPage' => '3',
      'rawOption' => NULL,
    ),
    'passwd' => 
    array (
      'name' => 'passwd',
      'type' => 'text',
      'size' => 30,
      'zh' => '',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'nickname' => 
    array (
      'name' => 'nickname',
      'type' => 'text',
      'size' => 30,
      'zh' => '昵称',
      'rawOption' => NULL,
    ),
    'gender' => 
    array (
      'name' => 'gender',
      'type' => 'text',
      'size' => 10,
      'zh' => '',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'birthday' => 
    array (
      'name' => 'birthday',
      'type' => 'datePicker',
      'size' => 10,
      'zh' => '生日',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'phone' => 
    array (
      'name' => 'phone',
      'type' => 'text',
      'size' => 30,
      'zh' => '手机',
      'showPage' => '3',
      'rawOption' => NULL,
    ),
    'head' => 
    array (
      'name' => 'head',
      'type' => 'img',
      'size' => 30,
      'zh' => '图像',
      'showPage' => '0010',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'tpl_function' => 'show_img()',
    ),
    'ch' => 
    array (
      'name' => 'ch',
      'type' => 'text',
      'size' => 30,
      'zh' => '用户渠道',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'bind_ch' => 
    array (
      'name' => 'bind_ch',
      'type' => 'select',
      'size' => 10,
      'zh' => '绑定渠道',
      'showPage' => '0010',
      'checkType' => '',
      'options' => 
      array (
        0 => 'QQ',
        1 => '微信',
        2 => '微博',
        3 => '手机号注册',
        4 => '游客',
      ),
      'rawOption' => '0:QQ,1:微信,2:微博,3:手机号注册,4:游客',
      'show_text' => 'bind_ch_text',
    ),
    'bind_id' => 
    array (
      'name' => 'bind_id',
      'type' => 'text',
      'size' => 30,
      'zh' => '第三方标识ID',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'bind_name' => 
    array (
      'name' => 'bind_name',
      'type' => 'text',
      'size' => 30,
      'zh' => '第三方昵称',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'bind_head' => 
    array (
      'name' => 'bind_head',
      'type' => 'text',
      'size' => 30,
      'zh' => '第三方头像',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'deviceid' => 
    array (
      'name' => 'deviceid',
      'type' => 'text',
      'size' => 30,
      'zh' => '设备id',
      'showPage' => '0011',
      'rawOption' => NULL,
    ),
    'address' => 
    array (
      'name' => 'address',
      'type' => 'textarea',
      'row' => 10,
      'zh' => '地址',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'tel' => 
    array (
      'name' => 'tel',
      'type' => 'text',
      'size' => 30,
      'zh' => '电话',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'cname' => 
    array (
      'name' => 'cname',
      'type' => 'text',
      'size' => 30,
      'zh' => '姓名',
      'rawOption' => NULL,
    ),
    'appid' => 
    array (
      'name' => 'appid',
      'type' => 'text',
      'size' => 30,
      'zh' => '应用id',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'balance' => 
    array (
      'name' => 'balance',
      'type' => 'text',
      'size' => 10,
      'zh' => '余额',
      'showPage' => '1110',
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
    'login_time' => 
    array (
      'name' => 'login_time',
      'type' => 'text',
      'size' => 10,
      'zh' => '登录时间',
      'showPage' => '0',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'function' => 'date("y-m-d h:i:s",###)',
    ),
    'platform' => 
    array (
      'name' => 'platform',
      'type' => 'select',
      'size' => 10,
      'zh' => '平台',
      'showPage' => '0011',
      'checkType' => '',
      'options' => 
      array (
        1 => 'android',
        2 => 'iOS',
      ),
      'rawOption' => '1:android,2:iOS',
      'show_text' => 'platform_text',
    ),
    'ip' => 
    array (
      'name' => 'ip',
      'type' => 'text',
      'size' => 30,
      'zh' => 'ip',
      'showPage' => '0011',
      'rawOption' => NULL,
    ),
    'area' => 
    array (
      'name' => 'area',
      'type' => 'text',
      'size' => 30,
      'zh' => '区域',
      'showPage' => '2',
      'rawOption' => NULL,
    ),
    'memberno' => 
    array (
      'name' => 'memberno',
      'type' => 'text',
      'size' => 30,
      'zh' => '会员编号',
      'showPage' => '0011',
      'rawOption' => NULL,
    ),
    'login_mobile' => 
    array (
      'name' => 'login_mobile',
      'type' => 'text',
      'size' => 30,
      'zh' => '登录手机',
      'showPage' => '0011',
      'rawOption' => NULL,
    ),
    'unwin_return_point' => 
    array (
      'name' => 'unwin_return_point',
      'type' => '',
      'size' => 10,
      'zh' => '不中返',
      'tips' => '(比例)判断字段',
      'showPage' => '2',
      'rawOption' => NULL,
    ),
    'is_halfman' => 
    array (
      'name' => 'is_halfman',
      'type' => '',
      'size' => 10,
      'zh' => '半真人',
      'tips' => '用于黑名单用户',
      'showPage' => '2',
      'checkType' => '',
      'options' => 
      array (
        0 => '否',
        1 => '是',
      ),
      'rawOption' => '0:否,1:是',
      'show_text' => 'is_halfman_text',
    ),
    'status_flag' => 
    array (
      'name' => 'status_flag',
      'type' => 'text',
      'size' => 10,
      'zh' => '用户状态',
      'showPage' => '0010',
      'checkType' => '',
      'options' => 
      array (
        0 => '禁用',
        1 => '正常',
      ),
      'rawOption' => '0:禁用,1:正常',
      'show_text' => 'status_flag_text',
    ),
    'degree' => 
    array (
      'name' => 'degree',
      'type' => 'text',
      'size' => 10,
      'zh' => '熟练程度',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
    'unique_id' => 
    array (
      'name' => 'unique_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '用户惟一标识',
      'showPage' => '0011',
      'rawOption' => NULL,
    ),
    'recharge_amount' => 
    array (
      'name' => 'recharge_amount',
      'type' => 'text',
      'size' => 10,
      'zh' => '充值总额',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'income' => 
    array (
      'name' => 'income',
      'type' => 'text',
      'size' => 10,
      'zh' => '收益',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'roi' => 
    array (
      'name' => 'roi',
      'type' => 'text',
      'size' => 10,
      'zh' => 'roi',
      'showPage' => '1110',
      'rawOption' => NULL,
    ),
    'estimate_account_num' => 
    array (
      'name' => 'estimate_account_num',
      'type' => 'text',
      'size' => 10,
      'zh' => '账号数',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'alipay_name' => 
    array (
      'name' => 'alipay_name',
      'type' => 'text',
      'size' => 30,
      'zh' => '支付宝名',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'alipay_account' => 
    array (
      'name' => 'alipay_account',
      'type' => 'text',
      'size' => 30,
      'zh' => '支付宝账号',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'update_time' => 
    array (
      'name' => 'update_time',
      'type' => 'time',
      'zh' => '更新时间',
      'showPage' => '0',
      'rawOption' => NULL,
    ),
  ),
  'add' => 
  array (
    'id' => 
    array (
    ),
    'nickname' => 
    array (
    ),
    'cname' => 
    array (
    ),
    'balance' => 
    array (
    ),
    'roi' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'id' => 
    array (
    ),
    'nickname' => 
    array (
    ),
    'cname' => 
    array (
    ),
    'balance' => 
    array (
    ),
    'roi' => 
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
    'nickname' => 
    array (
    ),
    'phone' => 
    array (
    ),
    'head' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'bind_ch' => 
    array (
    ),
    'bind_name' => 
    array (
    ),
    'deviceid' => 
    array (
    ),
    'address' => 
    array (
    ),
    'tel' => 
    array (
    ),
    'cname' => 
    array (
    ),
    'balance' => 
    array (
    ),
    'modify_t' => 
    array (
    ),
    'platform' => 
    array (
    ),
    'ip' => 
    array (
    ),
    'area' => 
    array (
    ),
    'memberno' => 
    array (
    ),
    'login_mobile' => 
    array (
    ),
    'unwin_return_point' => 
    array (
    ),
    'is_halfman' => 
    array (
    ),
    'status_flag' => 
    array (
    ),
    'unique_id' => 
    array (
    ),
    'recharge_amount' => 
    array (
    ),
    'income' => 
    array (
    ),
    'roi' => 
    array (
    ),
    'estimate_account_num' => 
    array (
    ),
    'alipay_name' => 
    array (
    ),
    'alipay_account' => 
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
    'nickname' => 
    array (
    ),
    'phone' => 
    array (
    ),
    'deviceid' => 
    array (
    ),
    'cname' => 
    array (
    ),
    'platform' => 
    array (
    ),
    'ip' => 
    array (
    ),
    'memberno' => 
    array (
    ),
    'login_mobile' => 
    array (
    ),
    'unique_id' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '用户',
    'property' => '',
    'action' => 'edit:编辑:id,view_recharge:查看充值记录:openid',
    'pageButton' => 
    array (
    ),
    'name' => 'pm_user',
  ),
);