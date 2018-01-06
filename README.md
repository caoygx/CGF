
# CGF(Comment Generate Form) 不写一行代码，实现增删改查

这是一种表字段注释的格式，一种思想，不限于任何编程语言，只要各语言实现了这种方式，就能自动实现CURD。  

根据表字段注释，自动实现添加,修改，列表，搜索基本功能。    
只要定义好字段的注释，就能实现增删改查。大大提高开发效率，让你真正飞起来！   

希望大家可以一起来完善这种格式，让其变成一种标准或协议。  

# 安装 install
由于demo是基于thinkphp的，所以要下载thinkphp框架,并且将这两个目录是平级的。  

1. 创建目录 demo,然后进入demo目录  (linux下命令：mkdir demo   cd demo)
2. git clone https://github.com/caoygx/ThinkPHP.git
3. git clone https://github.com/caoygx/CGF.git
4. 创建表结构，并更改CGF/config.php的数据库配置
5. 浏览器打开 http://localhost/CGF/



# demo 样例(基于thinkphp实现的)  
http://cgf.rrbrr.com/

大家可以连上测试数据库，增加些表，试用下。请不要删除原来的表。

数据库地址    qdm112455516.my3w.com  
数据库账号    qdm112455516  
数据库密码    cgfrrbrr  


# 格式说明  

以 |-，之类的做分隔  
注释标题 - htm控件类型 - 提示 |展现页面 | 校验类型 |  选项  

## 第一部分 描述字段lable、控件类型、提示信息
注释标题: 一般是字段的中文标题，form表单的label  
html控件类型: select,checkbox,input,textare,datepicker,editor等,更多的控件需要你自己来实现。  
提示:一般是此字段的填写规范，如：允许字母或数字  


## 第二部分 描述在哪些页面显示
展现页面:用位表示   
1       1          1       1  
添加  修改    列表   搜索项  
8       4           2      1  
可以用每1位的10进制数相加的和表示，也可以直接用二进制表示  
1011(二进制)  等价于 11(十进制)  

例:  
添加,修改，列表,搜索全显示 1111 = 15  
添加,修改，列表都要显示则是  1110 = 14  
添加，修改显示，列表不显示    1100 = 10  
添加，修改不显示，列表显示，一般像创建时间就是这样  0010 = 1  


## 第三部分 描述前后台校验方式
校验类型:reqiure,email,username,mobile等,用于后台校验,对应thinkphp的校验格式  ，
也支持前台验证，目前使用validform验证格式，也可使用正则表达式。


## 第四部分   描述选项的key和value
选项： 选项1:选项1值，选项2：缺项2值  

## 第五部分   回调函数处理  
比如保存多选的tag时，页面上传过来的是个数组 tag = [a,b,c] ，但想保存为 a,b,c,则此字段可使用函数回调来处理  
写法 implode=\,,###
等号前面是函数名，后面是参数，用逗号分隔。像implode函数，第一参数就是逗号，则需要转义写为 \,

   

# 样例写法
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


# 表定义的参考格式
```sql

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标题-hidden|0111',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名--用户名为字符|1111|require:用户名必须填写-unique-<</\\w{3,6}/i>>:用户名不合法',
  `password` varchar(255) DEFAULT '' COMMENT '密码-password|1110|require:密码必须填写',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱|1111|require:邮箱必须填写-email:邮箱格式不正确',
  `birthday` date DEFAULT NULL COMMENT '生日|1111|require:密码必须填写',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态-select-禁用则不显示|1111|require|0:禁用,1:正常,2:审核中',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间|0010',
  `flag` varchar(255) NOT NULL DEFAULT '' COMMENT '标记-select|1100|require|0:推荐,1:置顶,2:广告',
  `intro` text COMMENT '用户介绍-editor|1100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;



```


# 函数使用
show_func 显示列表数据时，调用函数，参数为当前字段的值。

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