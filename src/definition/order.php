<?php
//base为相当于基类
//list,add,edit,search可继承覆盖
return [
    //字段基础信息定义，list,add,edit,search中如果有对应的字段，由对应的字段信息与base合并
    'base' => [
        'id'           => [
            'zh' => '编号22',
        ],
        'create_t'     => ['zh' => '订单时间'],
        'openid'       => [
            'zh' => '三方id',
        ],
        'order_id'     => [
            'zh' => '订单号'
        ],
        'act_goods_id' => [
            'zh' => '商品ID',
        ],
        'issue_id'=>['zh'=>'期号'],

        'goods_name'   => [
            'zh' => '商品名称',
        ],
        'status'       => [
            'zh'        => '订单状态',
            'show_text' => 'status_text',
            'type'      => 'select',
            'options'   => [
                0 => '默认',
                1 => '处理中',
                2 => '交易成功',
                3 => '交易失败',
            ],
        ], 'user_type' => [
            'zh'      => '用户类型',
            'type'    => 'select',
            'options' => [
                0 => '机器人',
                1 => '真人',
            ],
        ],

        'total_num'   => [
            'zh' => '拍卖次数',
        ],
        'already_num' => [
            'zh' => '参加次数'
        ],
        'unit_price'  => [
            'zh' => '单价',
        ],
        'bid_price'   => [
            'zh' => '奖品价格',
        ],

        'need_price' => [
            'zh' => '中奖等级',
        ],

        'need_price' => [
            'zh' => '采购价'
        ],

        'user_type'   => [
            'zh'      => '用户类型',
            'type'    => 'select',
            'options' => [
                0 => '机器人',
                1 => '真人',
            ],
        ],
        'status_flag' => [
            'zh'      => '用户状态',
            'type'    => 'select',
            'options' => [
                0 => '禁用',
                1 => '正常'
            ]
        ],

        'ch'       => ['zh' => '渠道'],
        'memberno' => ['zh' => '会员号'],

        'address'                => ['zh' => '地址'],
        'tel'                    => ['zh' => '电话'],
        'login_mobile'           => [
            'zh'      => '登录手机',
            'type'    => 'select',
            'options' => [
                0 => '未绑定',
                1 => '已绑定'
            ]
        ],
        'phone'           => [
            'zh' => '手机'
        ],
        'cname'                  => ['zh' => '姓名'],
        'goods_id'               => ['zh' => '商品id'],
        'name'                   => ['zh' => '商品名'],
        'realuser_auction_times' => ['zh' => '所有竞拍次数'],
        'win_cost'               => ['zh' => '中标次数'],
        'now_price'              => ['zh' => '现在价格'],

        'type' => [
            'zh'      => '商品类型',
            'type'    => 'select',
            'options' => [
                0 => '实物',
                1 => '虚拟卡',
                2 => '手机卡',
            ]],


        'send_no'     => ['zh' => '发货单号'],
        'order_no'    => ['zh' => '订单号'],
        'pur_price'   => ['zh' => '采购价'],
        'pur_channel' => ['zh' => '采购渠道'],
        'pur_t'       => ['zh' => '采购时间'],
        'express'     => ['zh' => '配送信息'],
        'express_no'  => ['zh' => '快递单号'],
        'mem_code'    => ['zh' => '会员充值号'],

    ],
    //公用列表
    'list' => [
        'id'           => [
            'zh' => '编号22',
        ],
        'create_t'     => ['zh' => '订单时间'],
        'openid'       => [
            'zh' => '三方id',
        ],
        'order_id'     => [
            'zh' => '订单号'
        ],
        'act_goods_id' => [
            'zh' => '商品ID',
        ],

        'goods_name' => [
            'zh' => '商品名称',
        ],
        'status'     => [
            'zh'        => '订单状态',
            'show_text' => 'status_text',
            'type'      => 'select',
            'options'   => [
                0 => '默认',
                1 => '处理中',
                2 => '交易成功',
                3 => '交易失败',
            ],
        ],

        'total_num'   => [
            'zh' => '拍卖次数',
        ],
        'already_num' => [
            'zh' => '参加次数'
        ],
        'unit_price'  => [
            'zh' => '单价',
        ],
        'bid_price'   => [
            'zh' => '奖品价格',
        ],

        'need_price' => [
            'zh' => '中奖等级',
        ],

        'need_price' => [
            'zh' => '采购价'
        ],

        'user_type' => [
            'zh'      => '用户类型',
            'type'    => 'select',
            'options' => [
                0 => '机器人',
                1 => '真人',
            ],
        ],
        'ch'        => ['zh' => '渠道'],
        'memberno'  => ['zh' => '会员号'],

        'address'                => ['zh' => '地址'],
        'tel'                    => ['zh' => '电话'],
        'login_mobile'           => [
            'zh' => '登录手机'
        ],
        'phone'           => [
            'zh' => '手机'
        ],
        'cname'                  => ['zh' => '姓名'],
        'goods_id'               => ['zh' => '商品id'],
        'name'                   => ['zh' => '商品名'],
        'realuser_auction_times' => ['zh' => '所有竞拍次数'],
        'win_cost'               => ['zh' => '中标次数'],
        'now_price'              => ['zh' => '现在价格'],

        'type' => ['zh' => '商品类型'],

        'send_no'     => ['zh' => '发货单号'],
        'order_no'    => ['zh' => '订单号'],
        'pur_price'   => ['zh' => '采购价'],
        'pur_channel' => ['zh' => '采购渠道'],
        'pur_t'       => ['zh' => '采购时间'],
        'express'     => ['zh' => '配送信息'],
        'express_no'  => ['zh' => '快递单号'],
        'mem_code'    => ['zh' => '会员充值号'],

    ],


    'edit' => [
        'act_goods_name' => [
            'type'       => 'text',
            'size'       => 10,
            'validation' => 'mobile-unique',
        ],
        'content'        => [
            'type'      => 'editor',
            'size'      => 10,
            'component' => 'fck',//编辑器组件 kindeditor
        ],
        'create_t'       => [
            'type'   => 'datetimePicker',//时间
            'format' => 'y-m-d H:i:s',
        ],
        /* 'ch' => [
             'type' => 'select',//文件上传
         ],*/
        'draw_state'     => [
            'type'    => 'select',
            'options' => [
                0 => '未中奖',
                1 => '已中奖'
            ],
        ],
        'memberno'       => [
            'type' => 'text',
        ]
    ],

    'add' => [
        'act_goods_name' => [
            'type' => 'text',
        ],
        'create_t'       => [
            'type'   => 'datetimePicker',//时间
            'format' => 'y-m-d H:i:s',
        ],
        /* 'ch' => [
             'type' => 'select',//文件上传
         ],*/
        'draw_state'     => [
            'type'    => 'select',
            'options' => [
                0 => '未中奖',
                1 => '已中奖'
            ],
        ],
        'memberno'       => [
            'type' => 'text',
        ]
    ],

    'search' => [
        //商品名
        'user_type' => [

            'size' => 10,
            'zh'   => '用户类型',
        ],

        'goods_name' => [],
        'ch'         => ['type' => 'text',],
        'status'     => [],
        'memberno'   => [
            'zh'   => '会员号',
            'type' => 'text',
        ],

    ],

    'module' => [
        'admin' => [
            "add"  => ['user_id' => []],
            "edit" => ['user_id' => []],
            "list" => ['user_id' => []],

        ],
        'user'  => [
            "list" => ['user_id' => []]
        ],
        'home'  => [
            "list" => ['user_id' => []]
        ],
    ],

    'tableInfo' =>
        array(
            'title'    => '用户',
            'property' => 'lock',
            'action'   => 'edit:编辑:id,view_recharge:查看充值记录:openid',
            'name'     => 'pm_user',
        ),

];
