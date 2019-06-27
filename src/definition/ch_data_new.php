<?php 
 return array (
  'base' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'hidden',
      'size' => 10,
      'zh' => '编号',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'ch' => 
    array (
      'name' => 'ch',
      'type' => 'select',
      'size' => 30,
      'zh' => '渠道',
      'showPage' => '1111',
      'checkType' => '',
      'options' => 
      array (
        'function' => 'ch_options(p1)',
      ),
      'rawOption' => 'function=ch_options(p1)',
      'show_text' => 'ch_text',
    ),
    'new_user' => 
    array (
      'name' => 'new_user',
      'type' => 'text',
      'size' => 10,
      'zh' => '日新增',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'reg_user' => 
    array (
      'name' => 'reg_user',
      'type' => 'text',
      'size' => 10,
      'zh' => '日注册',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'new_reg_rate' => 
    array (
      'name' => 'new_reg_rate',
      'type' => 'text',
      'size' => 10,
      'zh' => '新增到注册转化率',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'login_count' => 
    array (
      'name' => 'login_count',
      'type' => 'text',
      'size' => 10,
      'zh' => '日活跃用户数',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'user_count' => 
    array (
      'name' => 'user_count',
      'type' => 'text',
      'size' => 10,
      'zh' => '日充值用户数',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'user_money' => 
    array (
      'name' => 'user_money',
      'type' => 'text',
      'size' => 10,
      'zh' => '日充值金额',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'urppu' => 
    array (
      'name' => 'urppu',
      'type' => 'text',
      'size' => 30,
      'zh' => '平均充值',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'urpu' => 
    array (
      'name' => 'urpu',
      'type' => 'text',
      'size' => 30,
      'zh' => '活跃平均充值',
      'showPage' => '0010',
      'rawOption' => NULL,
    ),
    'create_t' => 
    array (
      'name' => 'create_t',
      'type' => 'dateRangePicker',
      'size' => 30,
      'zh' => '日期',
      'rawOption' => NULL,
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'ch' => 
    array (
    ),
    'new_user' => 
    array (
    ),
    'reg_user' => 
    array (
    ),
    'new_reg_rate' => 
    array (
    ),
    'login_count' => 
    array (
    ),
    'user_count' => 
    array (
    ),
    'user_money' => 
    array (
    ),
    'urppu' => 
    array (
    ),
    'urpu' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'add' => 
  array (
    'ch' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'ch' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'search' => 
  array (
    'ch' => 
    array (
    ),
    'create_t' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '渠道统计',
    'property' => '',
    'action' => '',
    'sort' => 
    array (
      0 => 'create_t',
      1 => 'desc',
    ),
    'pageButton' => 
    array (
      0 => '',
    ),
    'function' => NULL,
    'name' => 'pm_ch_data_new',
  ),
);