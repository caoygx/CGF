<?php
//base为相当于基类
//list,add,edit,search可继承覆盖
return [


        "base" =>[
            "id"=>[
                'zh'=>'编号',
            ],
            "openid" => [
                "zh" => "三方id",
            ],

            "act_issue_id" => [
                "zh" => "期号",
            ],

            "act_goods_name" => [
                "zh" => "活动商品",
            ],

            "prize_price" => [
                "zh" => "奖品价格",
            ],

            "prize_level" => [
                "zh" => "中奖等级",
            ],

            "auc_count" => [
                "zh" => "竞拍次数"
            ],
            "prize_goods_name" => [
                "zh" => "奖品名称",
            ],
            "purchase_price" => [
                "zh" => "采购价格",
                "type" => "text",
            ],
            "draw_state" => [
                "zh" => "抽奖状态",
                "show_text" => "draw_state_text",
                "options" => [
                    0 => "未抽奖",
                    1 => "已抽奖"
                ],
            ],
            "prize_state" => [
                "zh" => "中奖状态",
                "show_text" => "prize_state_text",
                "options" => [
                    0 => "未中奖",
                    1 => "已中奖"
                ],
            ],
            "trans_state" => [
                "zh" => "发奖状态",
                "show_text" => "trans_state_text",
                "options" => [
                    0 => "未发奖",
                    1 => "已发奖"
                ],
                "class"=>"c_trans_state",//给列表表格单元格增加样式，便于js能定位到相应的单元格
            ],
            "trans_kuaidi_num" => [
                "zh" => "快递单号",
            ],
            "create_t" => [
                "zh" => "创建时间",
            ],
            "act_goods_id" => [
                "zh" => "商品id",
                //"function"=>"showGoodTitle=参数1,参数2",
                //"function"=>function($a,$b){},
            ],
        ],
        //公用列表
        "list" => [
            "id"=>[],
            //会员号,渠道
            "openid" => [
                "related_table" => [
                    "table_name" => "user",
                    "fields" => ["memberno", "ch"],
                    "way" => "replace",//replace 1.add表示显示user_id，并且增加field定义的字段 2.replace 表示用field字段替换掉user_id
                ]
            ],

            //期号
            "act_issue_id" => [
                "related_table" => [
                    "related_field" => "issue_id",
                    "table_name" => "goods_activity",
                    "fields" => ["state"],
                    "way" => "add",//replace 1.add表示显示user_id，并且增加field定义的字段 2.replace 表示用field字段替换掉user_id
                ]
            ],

            "trans_state" => [
                "class"=>"c_trans_state",//给列表表格单元格增加样式，便于js能定位到相应的单元格
            ],

            "prize_state"=>[
                "show_text" => "prize_state_text",
            ],


            "act_issue_id" => [
            ],

            "act_goods_name" => [
            ],

            "prize_price" => [
            ],

            "prize_level" => [
            ],

            "auc_count" => [
            ],
            "prize_goods_name" => [
            ],
            "purchase_price" => [
            ],
            "draw_state" => [
            ],
            "prize_state" => [
            ],
            "trans_state" => [
            ],
            "trans_kuaidi_num" => [
            ],
            "create_t" => [
            ],
            "act_goods_id" => [
            ],

        ],


        "edit" => [
            "act_goods_name" => [
                "type" => "text",
                "size" => 10,
                "validation" => "mobile-unique",
            ],
            "content" => [
                "type" => "editor",
                "size" => 10,
                "component" => "fck",//编辑器组件 kindeditor
            ],
            "create_t" => [
                "type" => "datetimePicker",//时间
                "format" => "y-m-d H:i:s",
            ],
            /* "ch" => [
                 "type" => "select",//文件上传
             ],*/
            "draw_state" => [
                "type" => "select",
                "options" => [
                    0 => "未中奖",
                    1 => "已中奖"
                ],
            ],
            "memberno" => [
                "type" => "text",
            ]
        ],

        "add" => [
            "act_goods_name" => [
                "type" => "text",
            ],
            "create_t" => [
                "type" => "datetimePicker",//时间
                "format" => "y-m-d H:i:s",
            ],
            /* "ch" => [
                 "type" => "select",//文件上传
             ],*/
            "draw_state" => [
                "type" => "select",
                "options" => [
                    0 => "未中奖",
                    1 => "已中奖"
                ],
            ],
            "memberno" => [
                "type" => "text",
            ]
        ],

        "search" => [
            //商品名
            "act_goods_name" => [
                "zh" => "活动商品",
                "size" => 10,
                "validation" => "mobile-unique",
            ],

            "create_t" => [
                "zh" => "时间",
                "type" => "datetimePicker",//时间
                "format" => "y-m-d H:i:s",
            ],


            "memberno" => [
                "zh" => "会员号",
                "type" => "text",
            ],
            "ch" => [
                "zh" => "渠道",
                "type" => "text",
            ]
        ],

    'tableInfo' =>
        array (
            'title' => '抽奖',
            'property' => 'lock',
            //'action' => 'edit:编辑:id,view_recharge:查看充值记录:openid',
            'name' => 'pm_prize_draw_log',
        ),

];
