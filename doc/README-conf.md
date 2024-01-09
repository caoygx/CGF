#目录结构和设计说明
definition 是所有表定义
Form下是具体表单的实现类，继承Form.class.php
Validate下是各框架下的验证实现，继承Validate.php


#definition结构说明
#### [模块名][页名] 

### 模块名有下列项目  
admin  后台
user  用户中心
home  前台


### 页面有下列项目  
all 所有字段定义，等于list定义
add  添加页
edit  
list  
search  

# 数组定义使用方法

## 公共属性

##### name 表单名，一般和字段名相同  
##### type 表单类型，所有表单类型的实现都在Form目录下，要继承Form类  
1. 所有html表单类型都可以。如text,select
2. 自定义类型   
    editor 富文件编辑器  
    img 图片上传，带图片预览  
    file 文件上传  
    files 多文件上传  
    datepicker(日期选择控件)  
    datetimePicker(时间选择控件)  
    datePickerRang(日期范围选择控件)  
    datetimePickerRang(时间范围选择控件)  
    
##### size 表单的尺寸，一般用于text文本框  
##### zh   中文标签名  
##### options 当表单类型是select时的选项，checkbox,radio也可以有此字段。  
      1.枚举格式。 如 
```php
   
      'options'=>[
          0=>'禁用'
          1=>'正常'，
          2=>'审核中'
        ]
```   
      2.函数获取。 如
```php      
      'options'=>[
          'function'=>"get_allf_lag('a','###','@@@')"
        ]
```
##### rawOption 数据表字段的原生定义，一般只有表字段定义了选项，才会有。  
    如 'rawOption' => '0:否,1:是' 
##### validate 验证规则
验证规则可以有多个，会依次执行验证。
```php
    'validate' => [
        ['require:必须填写',]
        ['<</\w{3,6}/i>> : 用户名不合法'],
        ['checkUsername:用户名已经被使用了'],
    ]
```    

##### autoComplete





#### 实例

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

## base
base 通常是表的所有字段字义，其它表的定义如果要显示关联表的某些字段值，那些显示的字段的信息就是从base里取出来的。
例如： 
  搜索框增加选项步骤
    1.可在base里添加相应的字段。
    2.在search里增加。
    

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


