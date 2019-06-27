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
    'name' => 
    array (
      'name' => 'name',
      'type' => 'text',
      'size' => 30,
      'zh' => '活动名',
      'rawOption' => NULL,
    ),
    'corner_id' => 
    array (
      'name' => 'corner_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '角标',
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
        1 => '开',
        2 => '关',
      ),
      'rawOption' => '1:开,2:关',
      'show_text' => 'status_text',
    ),
    'start_t' => 
    array (
      'name' => 'start_t',
      'type' => 'datetimePicker',
      'size' => 10,
      'zh' => '开始时间',
      'showPage' => '1111',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'function' => 'date("y-m-d h:i:s",###)',
    ),
    'end_t' => 
    array (
      'name' => 'end_t',
      'type' => 'datetime',
      'size' => 10,
      'zh' => '结束时间',
      'showPage' => '1110',
      'checkType' => '',
      'options' => '',
      'rawOption' => '',
      'function' => 'date("y-m-d h:i:s",###)',
    ),
    'count' => 
    array (
      'name' => 'count',
      'type' => '',
      'size' => 10,
      'zh' => '倒计时',
      'tips' => '单位小时',
      'rawOption' => NULL,
    ),
    'return_rate' => 
    array (
      'name' => 'return_rate',
      'type' => 'text',
      'size' => 30,
      'zh' => '返币比例',
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
      'showPage' => '0',
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
      'zh' => 'user_id',
      'showPage' => '1111',
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
    'corner_id' => 
    array (
    ),
    'status' => 
    array (
    ),
    'start_t' => 
    array (
    ),
    'end_t' => 
    array (
    ),
    'count' => 
    array (
    ),
    'return_rate' => 
    array (
    ),
    'user_id' => 
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
    'corner_id' => 
    array (
    ),
    'status' => 
    array (
    ),
    'start_t' => 
    array (
    ),
    'end_t' => 
    array (
    ),
    'count' => 
    array (
    ),
    'return_rate' => 
    array (
    ),
    'user_id' => 
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
    'corner_id' => 
    array (
    ),
    'status' => 
    array (
    ),
    'start_t' => 
    array (
    ),
    'end_t' => 
    array (
    ),
    'count' => 
    array (
    ),
    'return_rate' => 
    array (
    ),
    'user_id' => 
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
    'corner_id' => 
    array (
    ),
    'status' => 
    array (
    ),
    'start_t' => 
    array (
    ),
    'count' => 
    array (
    ),
    'return_rate' => 
    array (
    ),
    'user_id' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '活动配置表',
    'property' => '',
    'action' => 'edit:编辑:id',
    'sort' => 
    array (
      0 => '',
    ),
    'pageButton' => 
    array (
      0 => 'add',
    ),
    'function' => NULL,
    'name' => 'pm_act_goods',
  ),
);