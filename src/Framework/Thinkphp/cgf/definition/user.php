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
    ),
    'username' => 
    array (
      'name' => 'username',
      'type' => 'text',
      'size' => 30,
      'zh' => 'username',
    ),
    'openid' => 
    array (
      'name' => 'openid',
      'type' => 'text',
      'size' => 30,
      'zh' => 'openid',
    ),
    'password' => 
    array (
      'name' => 'password',
      'type' => 'text',
      'size' => 30,
      'zh' => '密码',
    ),
    'nickname' => 
    array (
      'name' => 'nickname',
      'type' => 'text',
      'size' => 30,
      'zh' => '昵称',
    ),
    'gender' => 
    array (
      'name' => 'gender',
      'type' => 'text',
      'size' => 10,
      'rawOption' => '0:女,1:男',
      'options' => 
      array (
        0 => '女',
        1 => '男',
      ),
      'zh' => '性别',
      'show_text' => 'gender_text',
    ),
    'birthday' => 
    array (
      'name' => 'birthday',
      'type' => 'datePicker',
      'size' => 10,
      'zh' => '生日',
    ),
    'mobile' => 
    array (
      'name' => 'mobile',
      'type' => 'text',
      'size' => 30,
      'zh' => '手机',
    ),
    'avatar' => 
    array (
      'name' => 'avatar',
      'type' => 'img',
      'size' => 30,
      'function' => 'tpl_function=show_img()',
      'zh' => '图像',
    ),
    'ch' => 
    array (
      'name' => 'ch',
      'type' => 'text',
      'size' => 30,
      'zh' => '用户渠道',
    ),
    'deviceid' => 
    array (
      'name' => 'deviceid',
      'type' => 'text',
      'size' => 30,
      'zh' => '设备id',
    ),
    'address' => 
    array (
      'name' => 'address',
      'type' => 'textarea',
      'row' => 10,
      'zh' => '地址',
    ),
    'realname' => 
    array (
      'name' => 'realname',
      'type' => 'text',
      'size' => 30,
      'zh' => '姓名',
    ),
    'balance' => 
    array (
      'name' => 'balance',
      'type' => 'text',
      'size' => 10,
      'zh' => '余额',
    ),
    'create_t' => 
    array (
      'name' => 'create_t',
      'type' => 'text',
      'size' => 10,
      'function' => 'date("y-m-d h:i:s",###)',
      'zh' => '创建时间',
    ),
    'modify_t' => 
    array (
      'name' => 'modify_t',
      'type' => 'text',
      'size' => 10,
      'function' => 'date("y-m-d h:i:s",###)',
      'zh' => '修改时间',
    ),
    'login_time' => 
    array (
      'name' => 'login_time',
      'type' => 'text',
      'size' => 10,
      'function' => 'date("y-m-d h:i:s",###)',
      'zh' => '登录时间',
    ),
    'platform' => 
    array (
      'name' => 'platform',
      'type' => 'select',
      'size' => 10,
      'rawOption' => '1:android,2:iOS',
      'options' => 
      array (
        1 => 'android',
        2 => 'iOS',
      ),
      'zh' => '平台',
      'show_text' => 'platform_text',
    ),
    'ip' => 
    array (
      'name' => 'ip',
      'type' => 'text',
      'size' => 30,
      'zh' => 'ip',
    ),
    'memberno' => 
    array (
      'name' => 'memberno',
      'type' => 'text',
      'size' => 30,
      'zh' => '会员编号',
    ),
    'status_flag' => 
    array (
      'name' => 'status_flag',
      'type' => 'text',
      'size' => 10,
      'rawOption' => '0:禁用,1:正常',
      'options' => 
      array (
        0 => '禁用',
        1 => '正常',
      ),
      'zh' => '用户状态',
      'show_text' => 'status_flag_text',
    ),
    'update_time' => 
    array (
      'name' => 'update_time',
      'type' => 'time',
      'zh' => '更新时间',
    ),
    'company' => 
    array (
      'name' => 'company',
      'type' => 'text',
      'size' => 30,
      'zh' => '单位名称',
    ),
    'company_no' => 
    array (
      'name' => 'company_no',
      'type' => 'text',
      'size' => 30,
      'zh' => '单位编号',
    ),
    'industry' => 
    array (
      'name' => 'industry',
      'type' => 'text',
      'size' => 30,
      'zh' => '行业类型',
    ),
    'area' => 
    array (
      'name' => 'area',
      'type' => 'text',
      'size' => 30,
      'zh' => '区域',
    ),
    'staff_num' => 
    array (
      'name' => 'staff_num',
      'type' => 'text',
      'size' => 10,
      'zh' => '员工人数',
    ),
    'establish_time' => 
    array (
      'name' => 'establish_time',
      'type' => 'time',
      'zh' => '成立时间',
    ),
    'registered_capital' => 
    array (
      'name' => 'registered_capital',
      'type' => 'text',
      'size' => 30,
      'zh' => '注册资金',
    ),
    'company_type' => 
    array (
      'name' => 'company_type',
      'type' => 'text',
      'size' => 30,
      'rawOption' => '1:国有,2:民营,3:外资',
      'options' => 
      array (
        1 => '国有',
        2 => '民营',
        3 => '外资',
      ),
      'zh' => '单位性质',
      'show_text' => 'company_type_text',
    ),
    'contact' => 
    array (
      'name' => 'contact',
      'type' => 'text',
      'size' => 30,
      'zh' => '联系人',
    ),
    'position' => 
    array (
      'name' => 'position',
      'type' => 'text',
      'size' => 30,
      'zh' => '职位',
    ),
    'qq' => 
    array (
      'name' => 'qq',
      'type' => 'text',
      'size' => 30,
      'zh' => 'qq/微信',
    ),
    'email' => 
    array (
      'name' => 'email',
      'type' => 'text',
      'size' => 30,
      'zh' => '邮箱',
    ),
    'tel' => 
    array (
      'name' => 'tel',
      'type' => 'text',
      'size' => 30,
      'zh' => '电话',
    ),
    'contact_mobile' => 
    array (
      'name' => 'contact_mobile',
      'type' => 'text',
      'size' => 30,
      'zh' => '手机',
    ),
    'business_licence' => 
    array (
      'name' => 'business_licence',
      'type' => 'img',
      'size' => 30,
      'zh' => '营业执照',
    ),
    'identity_card' => 
    array (
      'name' => 'identity_card',
      'type' => 'img',
      'size' => 30,
      'zh' => '身份证',
    ),
    'intro' => 
    array (
      'name' => 'intro',
      'type' => 'text',
      'size' => 30,
      'zh' => '单位简介',
    ),
    'boss_contact' => 
    array (
      'name' => 'boss_contact',
      'type' => 'text',
      'size' => 30,
      'zh' => '老板联系人',
    ),
    'boss_gender' => 
    array (
      'name' => 'boss_gender',
      'type' => 'text',
      'size' => 10,
      'zh' => '老板性别',
    ),
    'boss_tel' => 
    array (
      'name' => 'boss_tel',
      'type' => 'text',
      'size' => 30,
      'zh' => '老板电话',
    ),
    'boss_mobile' => 
    array (
      'name' => 'boss_mobile',
      'type' => 'text',
      'size' => 30,
      'zh' => '老板手机',
    ),
    'boss_email' => 
    array (
      'name' => 'boss_email',
      'type' => 'text',
      'size' => 30,
      'zh' => '老板邮箱',
    ),
    'boss_qq' => 
    array (
      'name' => 'boss_qq',
      'type' => 'text',
      'size' => 30,
      'zh' => '老板qq',
    ),
    'type' => 
    array (
      'name' => 'type',
      'type' => 'text',
      'size' => 10,
      'rawOption' => '1:单位,2:商户,3:个人',
      'options' => 
      array (
        1 => '单位',
        2 => '商户',
        3 => '个人',
      ),
      'zh' => '类型',
      'show_text' => 'type_text',
    ),
  ),
  'add' => 
  array (
    'id' => 
    array (
    ),
    'username' => 
    array (
    ),
    'nickname' => 
    array (
    ),
    'gender' => 
    array (
    ),
    'realname' => 
    array (
    ),
    'balance' => 
    array (
    ),
    'company' => 
    array (
    ),
    'company_no' => 
    array (
    ),
    'industry' => 
    array (
    ),
    'area' => 
    array (
    ),
    'staff_num' => 
    array (
    ),
    'establish_time' => 
    array (
    ),
    'registered_capital' => 
    array (
    ),
    'company_type' => 
    array (
    ),
    'contact' => 
    array (
    ),
    'position' => 
    array (
    ),
    'qq' => 
    array (
    ),
    'email' => 
    array (
    ),
    'tel' => 
    array (
    ),
    'contact_mobile' => 
    array (
    ),
    'business_licence' => 
    array (
    ),
    'identity_card' => 
    array (
    ),
    'intro' => 
    array (
    ),
    'boss_contact' => 
    array (
    ),
    'boss_gender' => 
    array (
    ),
    'boss_tel' => 
    array (
    ),
    'boss_mobile' => 
    array (
    ),
    'boss_email' => 
    array (
    ),
    'boss_qq' => 
    array (
    ),
    'type' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'id' => 
    array (
    ),
    'username' => 
    array (
    ),
    'nickname' => 
    array (
    ),
    'gender' => 
    array (
    ),
    'realname' => 
    array (
    ),
    'balance' => 
    array (
    ),
    'company' => 
    array (
    ),
    'company_no' => 
    array (
    ),
    'industry' => 
    array (
    ),
    'area' => 
    array (
    ),
    'staff_num' => 
    array (
    ),
    'establish_time' => 
    array (
    ),
    'registered_capital' => 
    array (
    ),
    'company_type' => 
    array (
    ),
    'contact' => 
    array (
    ),
    'position' => 
    array (
    ),
    'qq' => 
    array (
    ),
    'email' => 
    array (
    ),
    'tel' => 
    array (
    ),
    'contact_mobile' => 
    array (
    ),
    'business_licence' => 
    array (
    ),
    'identity_card' => 
    array (
    ),
    'intro' => 
    array (
    ),
    'boss_contact' => 
    array (
    ),
    'boss_gender' => 
    array (
    ),
    'boss_tel' => 
    array (
    ),
    'boss_mobile' => 
    array (
    ),
    'boss_email' => 
    array (
    ),
    'boss_qq' => 
    array (
    ),
    'type' => 
    array (
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'username' => 
    array (
    ),
    'openid' => 
    array (
    ),
    'nickname' => 
    array (
    ),
    'gender' => 
    array (
    ),
    'mobile' => 
    array (
    ),
    'avatar' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'deviceid' => 
    array (
    ),
    'address' => 
    array (
    ),
    'realname' => 
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
    'memberno' => 
    array (
    ),
    'status_flag' => 
    array (
    ),
    'company' => 
    array (
    ),
    'company_no' => 
    array (
    ),
    'industry' => 
    array (
    ),
    'area' => 
    array (
    ),
    'staff_num' => 
    array (
    ),
    'establish_time' => 
    array (
    ),
    'registered_capital' => 
    array (
    ),
    'company_type' => 
    array (
    ),
    'contact' => 
    array (
    ),
    'position' => 
    array (
    ),
    'qq' => 
    array (
    ),
    'email' => 
    array (
    ),
    'tel' => 
    array (
    ),
    'contact_mobile' => 
    array (
    ),
    'business_licence' => 
    array (
    ),
    'identity_card' => 
    array (
    ),
    'intro' => 
    array (
    ),
    'boss_contact' => 
    array (
    ),
    'boss_gender' => 
    array (
    ),
    'boss_tel' => 
    array (
    ),
    'boss_mobile' => 
    array (
    ),
    'boss_email' => 
    array (
    ),
    'boss_qq' => 
    array (
    ),
    'type' => 
    array (
    ),
  ),
  'search' => 
  array (
    'id' => 
    array (
    ),
    'username' => 
    array (
    ),
    'openid' => 
    array (
    ),
    'nickname' => 
    array (
    ),
    'gender' => 
    array (
    ),
    'mobile' => 
    array (
    ),
    'deviceid' => 
    array (
    ),
    'realname' => 
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
    'company' => 
    array (
    ),
    'company_no' => 
    array (
    ),
    'industry' => 
    array (
    ),
    'area' => 
    array (
    ),
    'staff_num' => 
    array (
    ),
    'establish_time' => 
    array (
    ),
    'registered_capital' => 
    array (
    ),
    'company_type' => 
    array (
    ),
    'contact' => 
    array (
    ),
    'position' => 
    array (
    ),
    'qq' => 
    array (
    ),
    'email' => 
    array (
    ),
    'tel' => 
    array (
    ),
    'contact_mobile' => 
    array (
    ),
    'business_licence' => 
    array (
    ),
    'identity_card' => 
    array (
    ),
    'intro' => 
    array (
    ),
    'boss_contact' => 
    array (
    ),
    'boss_gender' => 
    array (
    ),
    'boss_tel' => 
    array (
    ),
    'boss_mobile' => 
    array (
    ),
    'boss_email' => 
    array (
    ),
    'boss_qq' => 
    array (
    ),
    'type' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'action' => 'edit:编辑:id,view_recharge:查看充值记录:openid',
    'property' => '',
    'title' => '用户',
    'name' => 'user',
  ),
);