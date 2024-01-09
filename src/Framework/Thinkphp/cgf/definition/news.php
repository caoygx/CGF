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
    ),
    'title' => 
    array (
      'name' => 'title',
      'type' => 'text',
      'size' => 30,
      'zh' => '标题',
    ),
    'category_id' => 
    array (
      'name' => 'category_id',
      'type' => 'select',
      'size' => 10,
      'rawOption' => '1:帮助,2:公司信息',
      'options' => 
      array (
        1 => '帮助',
        2 => '公司信息',
      ),
      'zh' => '分类',
      'show_text' => 'category_id_text',
    ),
    'content' => 
    array (
      'name' => 'content',
      'type' => 'editor',
      'row' => 10,
      'zh' => '内容',
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
    'url' => 
    array (
      'name' => 'url',
      'type' => 'text',
      'size' => 30,
      'zh' => '新闻链接',
    ),
    'status' => 
    array (
      'name' => 'status',
      'type' => 'text',
      'size' => 10,
      'rawOption' => '0:禁用,1:显示',
      'options' => 
      array (
        0 => '禁用',
        1 => '显示',
      ),
      'zh' => '状态',
      'show_text' => 'status_text',
    ),
    'merchant_id' => 
    array (
      'name' => 'merchant_id',
      'type' => 'text',
      'size' => 10,
      'zh' => '站点id',
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
    'category_id' => 
    array (
    ),
    'content' => 
    array (
    ),
    'url' => 
    array (
    ),
    'status' => 
    array (
    ),
    'merchant_id' => 
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
    'category_id' => 
    array (
    ),
    'create_time' => 
    array (
    ),
    'update_time' => 
    array (
    ),
    'url' => 
    array (
    ),
    'status' => 
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
    'title' => 
    array (
    ),
    'category_id' => 
    array (
    ),
    'url' => 
    array (
    ),
    'status' => 
    array (
    ),
    'merchant_id' => 
    array (
    ),
  ),
  'add' => 
  array (
    'title' => 
    array (
    ),
    'category_id' => 
    array (
    ),
    'content' => 
    array (
    ),
    'url' => 
    array (
    ),
    'status' => 
    array (
    ),
    'merchant_id' => 
    array (
    ),
  ),
  'tableInfo' => 
  array (
    'title' => '',
    'name' => 'news',
  ),
);