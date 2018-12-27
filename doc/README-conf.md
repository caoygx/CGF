



# 数组定义使用方法

## 公共
'type'=>'text,datepicker,datePickerRang',

zh  对应字段含义
base=>[
'flag'=>[
  'name'=>'标致',
  'type'=>'select',
  'options'=>[
    'function'=>"get_allf_lag('a','###','@@@')"
  ]
]

'create_time'=>[
  'name'=>'创建时间',
  'type'=>'datetime',
  ],


]


//添加页用时间选择组件
add=>[
'create_time'=>[
  'name'=>'创建时间',
  'type'=>'datetimePicker',
  ]
]

//搜索页用时间范围组件
search=>[
'create_time'=>[
  'name'=>'创建时间',
  'type'=>'datetimePickerRang',
  ]
]



//标题文本框
base=>[
  'title'=>[
  'name'=>'标题',
  'type'=>'input', //常规是文本框
  ]
]


search=>[
'title'=>[
  'name'=>'标题',
  'type'=>'linkbutton', //列表页是链接，点击进入编辑页或跳转到其它页面。只有后台这样。
  ]
]

对于create_time添加页和搜索页使用不同组件问题，sql不太好定义。最好用config.非要用sql,也用创建时间-datepicker|datetimePicker
但这种方式也不能解决各模块组件不同的问题。如用户中心搜索页，添加页和后台搜索页，添加页都用不同组件的问题。



## 组件
1.搜索页，根据配置生成输入框，日期选择控件等。但select的选项值却是在生成组件后，再调用函数生成的。
可以将select选项直接与输入框这些控件生成是一起生成。若是回调函数则也是生成组件时调用回调函数。这样避免生成select时来回交互。简化流程。这样就需要在form里实现自己完整的组件，不能依赖于tp的组件了。


## 列表

1.关联表
related_table 关联表定义，相当于join
related_field 关联表中对应的字段，相当于join时右表的关联字段 
table_name 关联表
fields => ["state","name"], 关联表要显示的字段
"way" => "add", 展现方式，
                add表示左表的字段也显示，右表字段也显示。
                replace表示左表字段不显示，用右表的字段代替
例：
      "related_table" => [
          "related_field" => "issue_id",
          "table_name" => "goods_activity",
          "fields" => ["state"],
          "way" => "add",//replace 1.add表示显示user_id，并且增加field定义的字段 2.replace 表示用field字段替换掉user_id
          'function'=>"date"
      ]

2.show_text  数字枚举字段，显示对应的文字含义

3. "class"=>"c_trans_state",//给列表表格单元格增加样式，便于js能定位到相应的单元格


函数定义
### 代表字段本身的值， 
@@@代表本行记录的值，有时需要引用其它字段的值参数计算，就用@@@传递行记录的所有值
col
1.'function'=>'date("y-m-d","###")'; //将被解析为 date("y-m-d",$v['col']);
2.'function'=>'getData('a','###','@@@')';//将被解析为 getData("a",$v['col'],$v);




## 搜索
## 编辑
## 添加


SqlToCgfDefinition.php
根据表定义生成cgf配置文件

sql表定义解析后的数组
```

"title"=>[
"name"=>"title",
"zh"=>"标题",
"arrShowPage"=>
 
            ["admin"]=>
              
               "add",
               "edit",
               "list",
               "search"
       
              ["user"]=>
              
                
               "add",
               "edit",
               "list",
               "search"
           
              ["home"]=>

               "list",
               "show"
    ]     
           
```   

1.单模块
$cgf=["base"=>['字段1'],"list"=>['字段1']]

2.多模块
$cgf['admin']=["base"=>['字段1','字段2','字段3'],"list"=>['字段1']]
$cgf['user']=["base"=>['字段1','字段2','字段3'],"list"=>['字段2']]
$cgf['home']=["base"=>['字段1','字段2','字段3'],"list"=>['字段3']]


# 不支持的功能
1.三表关联。如：订单表通过user_id关联用户表，用户表通过手机号关联所在地区表。这时想在订单列表显示用户手机号可以，但无法再关联到地区表，显示用户所在城市。


