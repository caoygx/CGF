<?php
//base为相当于基类
//list,add,edit,search可继承覆盖
return [
    //字段基础信息定义，list,add,edit,search中如果有对应的字段，由对应的字段信息与base合并
    'base' => [
        'id' => [
            'zh' => '编号22',
        ],
        'create_t' => ['zh' => '订单时间'],
        'openid' => [
            'zh' => '三方id',
        ],
        'order_id' => [
            'zh' => '订单号'
        ],
        'act_goods_id' => [
            'zh' => '商品ID',
        ],

        'goods_name' => [
            'zh' => '商品名称',
        ],
        'status' => [
            'zh' => '订单状态',
            'show_text' => 'status_text',
            'type'=>'select',
            'options' => [
                0 => '默认',
                1 => '处理中',
                2 => '交易成功',
                3 => '交易失败',
            ],
        ],

        'total_num' => [
            'zh' => '拍卖次数',
        ],
        'already_num' => [
            'zh' => '参加次数'
        ],
        'unit_price' => [
            'zh' => '单价',
        ],
        'bid_price' => [
            'zh' => '奖品价格',
        ],

        'need_price' => [
            'zh' => '中奖等级',
        ],

        'need_price' => [
            'zh' => '采购价'
        ],

        'user_type' => [
            'zh' => '用户类型',
            'type'=>'select',
            'options' => [
                0 => '机器人',
                1 => '真人',
            ],
        ],
        'ch' => ['zh' => '渠道'],

    ],
    //公用列表
    'list' => [

        'id' => [
        ],
        'create_t'=>[
            //'function'=>'date="y-m-d",###', //{$data.name|substr=###,0,3} //tp模板函数定义方式
            'function'=>'date("Y-m-d H:i:s","###")', //普通函数定义方式
        ],

        //会员号,渠道
        'openid' => [
            'related_table' => [
                'table_name' => 'user',
                'fields' => ['memberno', 'ch', 'address', 'tel', 'login_mobile', 'phone', 'cname'],
                'way' => 'replace',//replace 1.add表示显示user_id，并且增加field定义的字段 2.replace 表示用field字段替换掉user_id
            ]
        ],

        'order_id' => [
            'related_table' => [
                'table_name' => 'express',
                'related_field' => 'order_no',
                'fields' => ['send_no', 'pur_price', 'pur_channel', 'pur_t', 'express','express_no','mem_code'],
                'way' => 'add',//replace 1.add表示显示user_id，并且增加field定义的字段 2.replace 表示用field字段替换掉user_id
            ]
        ],
        'act_goods_id' => [
        ],

        'goods_name' => [
        ],
        'status' => [
        ],

        'total_num' => [
        ],
        'already_num' => [
        ],
        'unit_price' => [
        ],
        'bid_price' => [
        ],

        'need_price' => [
        ],

        'need_price' => [
        ]

        /* //期号
         'act_issue_id' => [
             'related_table' => [
                 'related_field' => 'issue_id',
                 'table_name' => 'goods_activity',
                 'fields' => ['state'],
                 'way' => 'add',//replace 1.add表示显示user_id，并且增加field定义的字段 2.replace 表示用field字段替换掉user_id
             ]
         ],

         'trans_state' => [
             'class' => 'c_trans_state',//给列表表格单元格增加样式，便于js能定位到相应的单元格
         ],

         'prize_state' => [
             'show_text' => 'prize_state_text',
         ],*/

    ],


    'edit' => [
        'act_goods_name' => [
            'type' => 'text',
            'size' => 10,
            'validation' => 'mobile-unique',
        ],
        'content' => [
            'type' => 'editor',
            'size' => 10,
            'component' => 'fck',//编辑器组件 kindeditor
        ],
        'create_t' => [
            'type' => 'datetimePicker',//时间
            'format' => 'y-m-d H:i:s',
        ],
        /* 'ch' => [
             'type' => 'select',//文件上传
         ],*/
        'draw_state' => [
            'type' => 'select',
            'options' => [
                0 => '未中奖',
                1 => '已中奖'
            ],
        ],
        'memberno' => [
            'type' => 'text',
        ]
    ],

    'add' => [
        'act_goods_name' => [
            'type' => 'text',
        ],
        'create_t' => [
            'type' => 'datetimePicker',//时间
            'format' => 'y-m-d H:i:s',
        ],
        /* 'ch' => [
             'type' => 'select',//文件上传
         ],*/
        'draw_state' => [
            'type' => 'select',
            'options' => [
                0 => '未中奖',
                1 => '已中奖'
            ],
        ],
        'memberno' => [
            'type' => 'text',
        ]
    ],

    'search' => [
        //商品名
        'user_type' => [

            'size' => 10,
            'zh' => '用户类型',
        ],

        'goods_name' => [ ],
        'ch' => [            'type' => 'text',        ],
        'status' => [ ],
        'memberno' => [
            'zh' => '会员号',
            'type' => 'text',
        ],

    ],

    'module' => [
        'admin' => [
            "add" => ['user_id' => []],
            "edit" => ['user_id' => []],
            "list" => ['user_id' => []],

        ],
        'user' => [
            "list" => ['user_id' => []]
        ],
        'home' => [
            "list" => ['user_id' => []]
        ],
    ],

  'tableInfo' => 
  array (
    'title' => '用户',
    'property' => 'lock',
    'action' => 'edit:编辑:id,view_recharge:查看充值记录:openid',
    'name' => 'pm_user',
  ),

];
