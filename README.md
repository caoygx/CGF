### 详细文档见doc目录中

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





 ==================================================================== 
 

# CGF(Comment Generate Form) 不写一行代码，实现增删改查

这是一种表字段注释的格式，一种思想，不限于任何编程语言，只要各语言实现了这种方式，就能自动实现CURD。  

根据表字段注释，自动实现添加,修改，列表，搜索基本功能。    
只要定义好字段的注释，就能实现增删改查。大大提高开发效率，让你真正飞起来！   

希望大家可以一起来完善这种格式，让其变成一种标准或协议。  

# 安装 install
composer require rrbrr/cgf



# 使用
参考demo写法




# demo 样例(基于thinkphp实现的)  
http://cgf.rrbrr.com/

大家可以连上测试数据库，增加些表，试用下。请不要删除原来的表。

数据库地址    qdm112455516.my3w.com  
数据库账号    qdm112455516  
数据库密码    cgfrrbrr  







# 表定义的参考格式
```sql

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标题-hidden|0111',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名--用户名为字符|1111-0011-11|require:用户名必须填写-unique-<</\\w{3,6}/i>>:用户名不合法',
  `password` varchar(255) DEFAULT '' COMMENT '密码-password|1100-1110-0|require:密码必须填写',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱|15-12-3|require:邮箱必须填写-email:邮箱格式不正确',
  `birthday` date DEFAULT NULL COMMENT '生日|1111|require:密码必须填写',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态-select-禁用则不显示|1111|require|0:禁用,1:正常,2:审核中',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间|0010',
  `flag` varchar(255) NOT NULL DEFAULT '' COMMENT '标记-select|1100|require|function=flag_options()|tpl_function=img()',
  `intro` text COMMENT '用户介绍-editor|1100-1100-11',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表|lock-birthday|编辑:id,查看用户浏览记录:id| add-export-showMenu';


```



# 参考格式说明
### 用户名--用户名为字符|1111|require:用户名必须填写-unique-<</\w{3,6}/i>>:用户名不合法    

#### [ 用户名 ] 控件label   
#### [ - ] 光有1个[ - ],没有内容，表示省略了内容，也就是省略了个控件类型，使用默认的控件类型
### [ 用户名为字符 ]，表示tip提示信息    
#### [ 1111 ] 表示用户名在增加，修改，列表，搜索页面都显示    
#### [ require ] 表示必填, unique表示为惟一,   
#### [ <</\w{3,6}/i>>:用户名不合法 ]   正则验证用户名，此正则意思是，用户名为3到6位的字符，并且不符合此正则的则报错 “用户名不合法”  



### 状态-select-禁用则不能访问 | 15| reqiure:必须填写  | 0:禁用,1:正常,2:审核中  
#### [ 状态 ]  控件label名为   
#### [ select ] 表示使用select控件  
####  [ 禁用则不能访问 ] 表示提示内容为   
#### [ 15 ] 为1111的10进制，等同于1111  
#### [ reqiure:必须填写 ] 表示必填，错误提示为：必须填写  
#### [ 0:禁用,1:正常,2:审核中 ]   表示状态这个select控件有3个选项，0,1,2表示key  






# 字段注释格式说明  

以 |-，之类的做分隔  
注释标题 - htm控件类型 - 提示 |后台显示页面-用户中心-前台显示页面 | 验证类型1-验证类型2 |  选项|显示模板  

## 第一部分 描述字段lable、控件类型、提示信息
注释标题: 一般是字段的中文标题，form表单的label  
html控件类型: select,checkbox,input,textare,datepicker,editor等,更多的控件需要你自己来实现。  
提示:一般是此字段的填写规范，如：允许字母或数字  


## 第二部分 描述在哪些页面显示


1. 位表示法(1表示显示，0不显示) 

添加页|修改页|列表页|搜索页
---|---|---|---
 1 | 1 | 0 | 0
 
 以上表示在添加和修改页显示此字段

2. 10进制表示法，就是上面的位表示法将二进制数转成10进制数
添加页|修改页|列表页|搜索页
---|---|---|---
 1 | 1 | 0 | 0
 
 1100 转成 10进制 = 12
 也就是说，12也表示添加页，修改页显示字段。

更多例子:  
添加,修改，列表,搜索全显示 1111 = 15  
添加,修改，列表都要显示则是  1110 = 14  
添加，修改显示，列表不显示    1100 = 10  
添加，修改不显示，列表显示，一般像创建时间就是这样  0010 = 1
  
  如果一个项目有多个模块，每个模块显示不同的字段。则可以根据模块来配置。多个模块之间用 - 分隔。目前内置3个模块
  后台 - 用户中心 - 前台
  如 1111-0011-11 表示后台所有页面显示，用户中心在列表和搜索页显示，前台在列表和详情页显示。
  由于前台一般只有列表和搜索页，所以只有2位。


## 第三部分 描述前后台校验方式
校验类型:reqiure,email,username,mobile等,用于后台校验,对应thinkphp的校验格式  ，
也支持前台验证，目前使用validform验证格式，也可使用正则表达式。


## 第四部分   描述选项的key和value
选项： 选项1:选项1值，选项2：缺项2值  

## 第五部分   回调函数处理  
比如保存多选的tag时，页面上传过来的是个数组 tag = [a,b,c] ，但想保存为 a,b,c,则此字段可使用函数回调来处理  
写法 implode=\,,###
等号前面是函数名，后面是参数，用逗号分隔。像implode函数，第一参数就是逗号，则需要转义写为 \\,

   




# 表本身注释格式说明

表的中文名|属性|操作|排序|页面按钮  


1. 表的中文名:表示表的作用。  

2. 属性: 可选值有 lock,lock表示生成相关cgf文件后，会锁定配置文件，再次修改不会生成新配置文件。  

3. 每行记录的操作:  
    记录可以进行的操作项  
    常用有：编辑，删除。  
    写法：edit:编辑:id, edit表示js的方法名，编辑表示页面上显示的中文名，id表示参数  

4. 排序  
    默认排序字段-倒序  

5. 页面按钮 页面上显示的按钮，用 - 分隔  
    可选值:add,export,showMenu
          add  显示添加按钮
          export 显示导出按钮  
          showMenu 在左侧菜单显示  

例：用户表|lock|edit:编辑:id,del:删除:id|create_time-desc|export-showMenu|function_name  

  




# 函数使用
1.show_func 显示列表数据时，调用函数，参数为当前字段的值。
2.tpl_function=img() 会被解析成配置 ['flag'=>['tpl_function'=>'img()']
3.选项字段也可定义函数，function=flag_options()将会调用flag_options返回所有选项

例1：显示支付方式。

```

 //cgf 格式
 `channel` varchar(255) DEFAULT '' COMMENT '支付渠道|0011-0-11|require|show_func=get_pay', 
 
 // channel值一般是 alipay,wxpay等。但希望显示出来的是支付宝，微信。就可以用函数来处理了。

 //php 代码
 function get_pay($selfValue){
    $paymethod = ['alipay'=>'支付宝','wxpay'=>'微信'];
    return $paymethod[selfValue];
 }
 
 //这样在列表里就能看到支付方式的汉字了，而不是字母代号


```


例2:多参数支持，显示产品详情链接.订单里显示产品名，想要点击能打开产品详情，详情页需要传产品id参数，可用下面方式。

方式一: 
```
//cgf格式
`course_title` varchar(255) NOT NULL DEFAULT '' COMMENT '产品名称|1011||show_func=order_course_title',

//php
function order_course_title($title,$key,$v){ // 调用代码：call_user_func_array(函数名,[字段值,字段名,此行记录所有数据]);
    
    $title = $v['course_title'];
    $url = "http://www.21mmm.com/course/{$v['course_id']}";
    $url = "<a href='{$url}' target='_blank' title='{$title}'>$title</a>";
    return $url;
}
```


方式二:在函数名后，指定参数course_id。 未来也可以实现可编程方式。
如: |fcuntion order_course_title(course_id), 直接写代码，然后通过程序来解释cgf代码
```
//cgf格式
`course_title` varchar(255) NOT NULL DEFAULT '' COMMENT '产品名称|1011||show_func=order_course_title-course_id',

//php
function order_course_title($title,$key,$course_id){
    
    $title = $v['course_title'];
    $url = "http://www.21mmm.com/course/{$v['course_id']}";
    $url = "<a href='{$url}' target='_blank' title='{$title}'>$title</a>";
    return $url;
}
```

未来支持默认函数调用，如order表user_id字段  "用户id|1111||show_func" 不用加=指定函数，只要定义了show_func,就会默认调用 order_user_id()这个函数







例2：显示分类名。  分类一般用分类id关联，但显示时，却希望显出中文。
```
// cgf格式
  `category_id` int(11) unsigned NOT NULL COMMENT '分类id-select|1111|require|function=get_select_by_category',
//php 代码
  function get_select_by_category(){
    $r = M('Category')->field('id,title')->select();
    return $r;  //从分类表，取出分类id,分类title

  }

  //这样category_id 显示的select就会是这样的 <option value="分类id1">分类tilte1</option>
```


# 配置

``` php 
$cgfConf                       = []; //
$cgfConf['dbConfig']           = ['host'=>'localhost','dbname'=>'test','username'=>'root','password'=>'123456','type'=>'mysql']; //数据库连接配置
$cgfConf['savePath']           = $appBasePath . "/Cgf/definition"; //保存cgf生成的定义文件
$cgfConf['framework']          = 'thinkphp'; //使用的框架
$cgfConf['validate']           = 'thinkphp'; //使用验证
$cgfConf['form']               = 'bootstrap'; //表单使用的框架
$cgfConf['currentName']        = 'common'; //当前模块名
$cgfConf['tableName']          = $tableName; //表名
$cgfConf['controllerName']     = $this->controllerName; //控制器名
$cgfConf['appRootPath']        = $appBasePath; //框架应用程序根目录
$cgfConf['parentTemplatePath'] = $appBasePath . '/view/public/'; //cgf生成模板使用的父模板,cgf会根据这里的模板来生成应用模板
$cgfConf['templateSavePath']   = $appBasePath . "/view/{$tableName}"; //cgf生成的模板保存路径
$cgfConf['availableModule']    = ['common', 'admin']; //可用模块
$cgfConf['autoHiddenPrimaryKey']    = true; //是否将主键表单类型设为hidden
```

 # 跨库支持
 ```
  $dbConnection= [
                    'DB_TYPE'=>'mysql',
                    'DB_HOST'=>'localhost',
                    'DB_PORT' => '3306',
                    'DB_NAME'=>'test',
                    'DB_USER'=>'root',
                    'DB_PWD'=>'123456',
                    'DB_PREFIX'=>'',
                  ]
  //在实例化的时候传入db连接即可
  $tableInfo = new TableInfo('edit',$dbConnection);
```





# 截图

![添加页面](https://github.com/caoygx/CGF/blob/master/screenshot/add.jpg)
![列表页面](https://github.com/caoygx/CGF/blob/master/screenshot/lists.jpg)



 
 # 其它扩展思路
 可以做个生成工具
 还可以用这个工具来直接生成json来描述，这样就是难看一点，但描述更清晰
 更进一步可以用工具来生成其它更精简的格式



 # 下一步支持
 1.字段的fuction可以区分是后端function,还是前端function.
 如排序字段，后台列表希望显示出的是个可编辑的input,这样直接就能在列表页更改排序。
 如果不区分的话，后台function将字段变成<input type="text"> ,前台列表接口sort字段获取到也是这个html,那就完完了。
 所以可以用php_function表示是个后端函数，tpl_function表示模板函数，js_fuction表示js函数

 如：后台商品表有display字段表示显示状态,数据库值为0,1.但在后台和用户中心希望通过函数转为文字，但返回到前台的接口，希望显示原始值，这样前端页面可以根据1或0来判断，是上架按钮，还是下架按钮。
但这种情况下，后台也需要有对应的功能。
商品名     显示状态                                          操作
苹果6手机  上架中(通过函数将display的值1转为文本“上架中”)      下架(通过if(dispaly==1)则显示此文字)

此时有两种办法
  1.if(display=='上架') 显示下架
  2.增加个display_text字段，列表展现时，显示display_text的内容
感觉都不科学



 可以试播-select|1111||0:否,1:是



用命名空间和作用域解决
list.function=show 列表调用函数
search.function=show 搜索调用函数
all.function=show //默认所有,可省略
admin.list.function=show   后台列表显示调用函数

显示关联表相应字段
user_id|1111||list.table=field(username,sex)-replace //replace替换,add默认add 











# 数组定义使用方法

## 公共
zh  对应字段含义

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




