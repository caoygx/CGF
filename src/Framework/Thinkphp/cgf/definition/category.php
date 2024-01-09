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
    'title' => 
    array (
      'name' => 'title',
      'type' => 'text',
      'size' => 30,
      'zh' => '关键字',
    ),
    'en' => 
    array (
      'name' => 'en',
      'type' => 'text',
      'size' => 30,
      'zh' => 'en',
    ),
    'parent_id' => 
    array (
      'name' => 'parent_id',
      'type' => 'text',
      'size' => 10,
      'zh' => 'parent_id',
    ),
    'create_time' => 
    array (
      'name' => 'create_time',
      'type' => 'time',
      'zh' => '创建时间',
    ),
    'status' => 
    array (
      'name' => 'status',
      'type' => 'text',
      'size' => 10,
      'rawOption' => '0:不采集,1:采集',
      'options' => 
      array (
        0 => '不采集',
        1 => '采集',
      ),
      'zh' => '状态',
      'show_text' => 'status_text',
    ),
    'last_gather_time' => 
    array (
      'name' => 'last_gather_time',
      'type' => 'text',
      'size' => 10,
      'zh' => '最后采集时间',
    ),
    'group' => 
    array (
      'name' => 'group',
      'type' => 'text',
      'size' => 30,
      'zh' => 'group',
    ),
    'level' => 
    array (
      'name' => 'level',
      'type' => 'text',
      'size' => 10,
      'zh' => 'level',
    ),
  ),
  'add' => 
  array (
    'id' => 
    array (
    ),
    'title' => 
    array (
    ),
    'en' => 
    array (
    ),
    'parent_id' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'status' => 
    array (
    ),
    'last_gather_time' => 
    array (
    ),
    'group' => 
    array (
    ),
    'level' => 
    array (
    ),
  ),
  'edit' => 
  array (
    'id' => 
    array (
    ),
    'title' => 
    array (
    ),
    'en' => 
    array (
    ),
    'parent_id' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'status' => 
    array (
    ),
    'last_gather_time' => 
    array (
    ),
    'group' => 
    array (
    ),
    'level' => 
    array (
    ),
  ),
  'list' => 
  array (
    'id' => 
    array (
    ),
    'title' => 
    array (
    ),
    'en' => 
    array (
    ),
    'parent_id' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'status' => 
    array (
    ),
    'last_gather_time' => 
    array (
    ),
    'group' => 
    array (
    ),
    'level' => 
    array (
    ),
  ),
  'search' => 
  array (
    'id' => 
    array (
    ),
    'title' => 
    array (
    ),
    'en' => 
    array (
    ),
    'parent_id' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'status' => 
    array (
    ),
    'last_gather_time' => 
    array (
    ),
    'group' => 
    array (
    ),
    'level' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'function' => NULL,
    'pageButton' => 
    array (
      0 => 'export',
      1 => 'add',
      2 => 'showMenu',
    ),
    'sort' => 
    array (
      0 => 'create_time',
      1 => 'desc',
    ),
    'action' => 'edit:编辑:id,del:删除:id',
    'property' => '',
    'title' => '关键字表',
    'name' => 'category',
  ),
);