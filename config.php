<?php
if(IS_CLI){
	define('LF',"\n");
}else{
	define('LF',"<br />");
}


    $custom = [
        'DB_TYPE' => "mysql",
        'DB_NAME' => 'doc',
        'DB_HOST' => "localhost",
        'DB_USER' => "root",
        'DB_PWD'  => "123456",
        'DB_PREFIX' => '',
        'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
    ];

$pub =  array(
    'SHOW_PAGE_TRACE' => true,
    'TAGLIB_PRE_LOAD' => 'html', //,OT\\TagLib\\Think
    'URL_MODEL'=>2, //默认1;URL模式：0 普通模式 1 PATHINFO 2 REWRITE 3 兼容模式
    'MODULE_ALLOW_LIST' => array('Home'),
	//'LOG_RECORD'=>true, 

    'options' => array(
		"bug_status"=>array ( 1 => '已收单', 2 => '已分级', 3 => '已分配', 4 => '已定位', 5 => '解决中', 6 => '已解决', 7 => '已上线', 8 => '已完结', 9 => '不是bug', ),
        "doc_status"=>array ( 1 => '是',0 => '否' ),
	),

    //默认操作
    "f_action" => 'status|showStatus=$user[\'id\'],edit:编辑:id,foreverdel:永久删除:id',
    'tpl_fields' => [
        "project" => [
            "f_list" => "id:编号|8%,title:信息名:edit,create_time|toDate='y-m-d':创建时间,status|getStatus2:状态",
            "f_action" => 'status|showStatus=$user[\'id\'],edit:编辑:id,foreverdel:永久删除:id',
            'f_add' => 'title,create_time',
        ],

    ]
	
);

return array_merge($pub,$custom);



