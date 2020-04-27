<?php

namespace Cgf;

/**
 * 解析cgf注释
 * Class parser
 * @package Cgf
 */
final class CommentParser
{

    const ADD = 8; //1000
    const EDIT = 4; //0100
    const LISTS = 2; //0010
    const SEARCH = 1; //0001

    const defaultShowAllColumn = true;

    //获取字段类型及长度
    public static function getColumnType($type)
    {
        //$type = "text";
        $typeInfo = [];
        if (strpos($type, "(") !== false) {
            if (preg_match("/(\w+)\((\d+)\)/", $type, $matches)) {

                $typeInfo["type"] = $matches[1];
                $typeInfo['size'] = $matches[2];

                //return $matches[0];
            } elseif (preg_match("/(\w+)\((.+)\)/", $type, $matches)) {
                $typeInfo["type"] = $matches[1];
                $typeInfo['size'] = $matches[2];
            }

        } else { //text
            $typeInfo["type"] = $type;
            $typeInfo['size'] = 65535;
        }
        return $typeInfo;

    }


    public static function getColumnAttribute($columnInfo)
    {
        $attribute = [];
        $attribute = self::getAttributeByColumnDefinition($columnInfo);
        if (!empty($columnInfo['COLUMN_COMMENT'])) {
            $commentAttribute = self::parseComment($columnInfo['COLUMN_COMMENT']);
            //var_dump($attribute,$commentAttribute);
            $attribute = array_merge($attribute, $commentAttribute);
        } elseif (self::defaultShowAllColumn) {
            $comment          = $columnInfo['COLUMN_NAME'] . "|1111";
            $commentAttribute = self::parseComment($comment);
            $attribute = array_merge($attribute, $commentAttribute);
        }

        //dump($attribute);
        return $attribute;

    }


    /**
     * 获取字段的表单属性
     * 如 表单类型，长度
     * @param $columnInfo
     * @return array
     */
    public static function getAttributeByColumnDefinition($columnInfo)
    {


        $inputAttribute         = [];
        $inputAttribute['name'] = $columnInfo['COLUMN_NAME'];
        $type                   = strtoupper($columnInfo['DATA_TYPE']);
        if ($columnInfo['COLUMN_KEY'] == 'PRI' && Cgf::$config['autoHiddenPrimaryKey']) { //主键设为隐藏
            $inputAttribute['type'] = "hidden";
            $inputAttribute['size'] = 10;
        } elseif (in_array($type, ["TINYINT", "SMALLINT", "MEDIUMINT", "INT", "BIGINT", "FLOAT", "DOUBLE", "DECIMAL"])) { //数字类型
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 10;
        } elseif (in_array($type, ["DATE", "YEAR"])) { //日期类型
            $inputAttribute['type'] = "date";
        } elseif (in_array($type, ["TIME", "DATETIME", "TIMESTAMP"])) {
            $inputAttribute['type'] = "time";
        } elseif (in_array($type, ["CHAR", "VARCHAR", "TINYBLOB", "TINYTEXT"])) { //小文本
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 30;
            if ($type == "varchar" && $columnInfo['size'] > 255) { //大文本域
                $inputAttribute['type'] = "textarea";
                $inputAttribute['row']  = 10;
            }
        } elseif (in_array($type, ["BLOB", "TEXT", "MEDIUMBLOB", "MEDIUMTEXT", "LONGBLOB", "LONGTEXT"])) {
            $inputAttribute['type'] = "textarea";
            $inputAttribute['row']  = 10;
        } else {
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 30;
        }

        return $inputAttribute;
    }


    /**
     * 获取在哪些页面显示
     * 返回示例 ['add','edit','list','search']
     * @param $showPage 页面标记 如:1100
     * @param $module 模块 取值:home,admin
     * @return array
     */
    public static function getShowPage($showPage, $module)
    {
        if (strlen($showPage) < 1 || $showPage == null) $showPage = 15;
        if (strlen($showPage) == 4) $showPage = bindec($showPage);
        $arrShowPages = [];

        if ($showPage & self::ADD) $arrShowPages[] = 'add';
        if ($showPage & self::EDIT) $arrShowPages[] = 'edit';
        if ($showPage & self::LISTS) {
            $arrShowPages[] = 'list';
            //$arrShowPages[] = 'index';
        }
        if ($showPage & self::SEARCH) $arrShowPages[] = 'search';

        if ($module == 'home') {
            if (isset($arrShowPages[0]) && $arrShowPages[0] == 'add') {
                $arrShowPages[0] = 'list';
            }

            if (isset($arrShowPages[1]) && $arrShowPages[1] == 'edit') {
                $arrShowPages[1] = 'show';
            }
            if (isset($arrShowPages[2])) unset($arrShowPages[2]);
            if (isset($arrShowPages[3])) unset($arrShowPages[3]);
        }
        return $arrShowPages;
    }


    /**
     * 解析字段注释
     *
     * 格式说明：
     * 以 |-，之类的做分隔
     * 注释标题 - htm控件类型 - 提示 | 展现页面 | 校验类型 | 选项 | 自动完成
     *
     * 注释标题: 一般是字段的中文标题，form表单的label
     * html控件类型: select,checkbox,input,textarea等
     * 提示:一般是此字段的填写规范，如：允许字母或数字
     * 校验类型:reqiure,email,username,mobile等,用于后台校验,对应thinkphp的校验格式
     *
     * 展现页面:用位表示
     * 1       1          1
     * 添加  修改    列表
     * 4       2           1
     *
     * 例:
     * 添加,修改，列表都要显示则是  111 =7
     * 添加，修改显示，列表不显示    110 = 6
     * 添加，修改不显示，列表显示，一般像创建时间就是这样  001 = 1
     * 如果要让这个字段在所有页面都不显，就设为 000=0
     *
     * 展现页面又分后台，用中心，前台三个模块，默认为所有模块配置，要指定其它模块用中划线分隔
     * 前台只有两个页面列表，详情
     * 1111-1100-01  后台全部显示-前台添加修改显示-前台列表不显示，详情显示
     * 后台也做为通用配置，如果用户中心，前台都没有配置，则用默认后台的配置
     *
     * 选项： 选项1:选项1值，选项2：缺项2值
     * 状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
     *
     *
     * 验证正则支持，这个比较蛋疼，由于正则里可以使用任意字符，与我们使用的分隔符有冲突，使用特殊符号将正则括起来
     * <<正则表达式>>,在此只允许验证里出现，否则就不知道<< >>括起来的什么了，注意目前<<>>不支持转义，如果正则表达式里还有<< >> 那就只能over了。
     *  首先将正则提取出来，再进行分隔
     *
     * 自动完成，用于保存时，自动调用相关涵数，来处理提交数据的。
     *
     * @param $comment
     * @return array
     */
    public static function parseComment($comment)
    {
        //先提取正则，避免正则里的特殊符号污染后面处理
        $reg         = '/<<(.+)>>/';
        $validateReg = '';
        preg_match($reg, $comment, $match);
        if (!empty($match[1])) {
            $validateReg = $match[1];
            $comment     = preg_replace($reg, 'reg', $comment);
        }

        //$comment = '状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中';
        //$id=$name='status';
        $ret = [];
        //$comment = '状态|0:禁用,1:正常,2:待审核';
        //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
        $arr = explode("|", $comment);
        $arr = array_map('trim', $arr);
        //array_walk($arr,$unction (&$v){ $v = trim($v); });
        $c = count($arr); //parseVarFunction

        $ret = [];

        /*$ret['showPage']  = '';//显示页
        $ret['checkType'] = ''; //校验类型
        $ret['options']   = ''; //选项
        $ret['function']  = ''; //函数


        $ret['zh']   = '';
        $ret['type'] = '';
        $ret['tips'] = '';
        $ret['flag'] = '';

        $ret['arrShowPages'] = [];

        $ret['arrRules'] = '';

        $ret['rawOption'] = '';


        $ret['validation'] = ''; //防止未定义报错
        $ret['size'] = ''; //防止未定义报错*/

        //还是不是能默认定义，因为这里type这些定义的优先级高于字段自己生成的type,会将自己生成的覆盖。
        //比如时间类型字段，如果此处给了默认空值，即使字段自动获取类型获取到了time类型，也不会在前端生成时间选择器。因为type的值是空，而不是date

        $showPage          = '';
        switch (true) {
            //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中 | implode=",",###
            case ($c >= 5):
                $ret['function'] = $arr[4];

            //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
            case ($c >= 4):
                $options = $arr[3];

                //选项分析

                if (!empty($options)) {
                    $ret['rawOption'] = $options; //原始option值

                    if (strpos($options, 'function') !== false) {
                        $func = explode('=', $options)[1];
                        /*$r = $func();
                        $options = [];
                        foreach ($r as $k => $v) {
                            $options[$v['id']] = $v['title'];
                        }*/
                        $options = ['function' => $func];
                        //var_dump($options);
                        //exit('x');
                    } else {
                        $arrOptions = [];
                        $items      = explode(",", $options);
                        $options    = [];
                        foreach ($items as $item) {
                            list($value, $text) = explode(':', $item);
                            $value              = trim($value);
                            $text               = trim($text);
                            $arrOptions[$value] = "$text";
                        }
                        $options = $arrOptions;
                    }
                    $ret['options'] = $options;
                }


            //状态-select-禁用则不能访问 | 7 | require
            case ($c >= 3):
                $checkType = $arr[2];
                //验证规则分析
                if (!empty($checkType)) {
                    $arrRules = [];
                    $allRules = explode("-", $checkType);
                    foreach ($allRules as $k => $v) {
                        $ruleInfo     = explode(':', $v);
                        $temp         = [];
                        $temp['type'] = $ruleInfo[0];
                        if ($ruleInfo[0] == 'reg') {
                            $temp['reg'] = $validateReg;
                        }

                        if ($ruleInfo[1]) {
                            $temp['msg'] = $ruleInfo[1];
                        }
                        $arrRules[] = $temp;
                    }
                    $ret['arrRules'] = $arrRules;
                }

            //状态-select-禁用则不能访问 | 1111-1110-1100
            case ($c >= 2):
                $showPage = trim($arr[1]);
                //if (!is_numeric($showPage)) E('显示页面属性必须是数字');

                $allModule = explode('-', $showPage);
                if (count($allModule) == 3) {
                    $admin = $allModule[0];
                    $user  = $allModule[1];
                    $home  = $allModule[2];
                    //var_dump($allModule,$admin,$user);
                } elseif (count($allModule) == 2) {
                    $admin = $allModule[0];
                    $user  = $allModule[1];
                    $home  = $admin;
                } else {
                    $admin = $allModule[0];
                    $user  = $admin;
                    $home  = $admin;
                }





            //状态-select-禁用则不能访问
            case ($c >= 1) :
                $title = trim($arr[0]);

                $arrTitle = explode("-", $title);
                $c        = count($arrTitle);
                switch ($c) {
                    //状态-select-禁用则不能访问
                    case ($c >= 4):
                        $flag = $arrTitle[3];
                        $ret['flag'] = $flag;

                    //状态-select-禁用则不能访问
                    case ($c >= 3):
                        $tips = $arrTitle[2];
                        $ret['tips'] = $tips;

                    //状态-select
                    case ($c >= 2):
                        $type = $arrTitle[1];
                        $ret['type'] = $type;

                    //状态
                    case ($c >= 1) :
                        $zh = $arrTitle[0];
                        $ret['zh'] = $zh;
                }

                //在哪些页面显示
                $arrShowPages          = [];
                $arrShowPages['admin'] = self::getShowPage($admin, 'admin');
                $arrShowPages['user']  = self::getShowPage($user, 'user');
                $arrShowPages['home']  = self::getShowPage($home, 'home');
                // var_dump($comment,$arrShowPages);
                // echo "====================================\n";
                $ret['arrShowPages'] = $arrShowPages;


        }

        /*       var_dump($title);
               var_dump($showPage);
               var_dump($checkType);
               var_dump($options);*/


        /* var_dump($name);
         var_dump($htmlType);
         var_dump($tips);*/


        //显示页面分析


        //echo $comment;
        //var_dump($arrShowPages);exit;






        //$ret['zh']
        //$ret = compact('zh', 'type', 'tips', 'flag', 'showPage', 'arrShowPages', 'checkType', 'arrRules', 'options', 'rawOption');

        if (!empty($function)) {
            $arrFunction = explode('=', $function); //处理 tpl_function=img()格式定义

            if (!empty($arrFunction[1])) {
                $ret[$arrFunction[0]] = $arrFunction[1];
            } else { //默认格式
                //$$functionKey = $function;
                $ret['function'] = $function;
            }
        }
        return $ret;
    }


    public static function parseTableComment($comment)
    {
        //$comment="问题反馈|xx|reply:回复";

        $ret = [];

        $pageButton = [];

        $arr = explode("|", $comment);
        $arr = array_map('trim', $arr);
        //array_walk($arr,public static function (&$v){ $v = trim($v); });
        $c = count($arr); //parseVarFunction
        switch (true) {
            //表名 | lock | edit:编辑:id,reply:回复:id | create_time-desc | export-showMenu | function_name
            case ($c >= 5):
                $ret['function'] = $arr[5];

            //表名 | lock | edit:编辑:id,reply:回复:id | create_time-desc | export-showMenu
            case ($c >= 5):
                $pageButton        = $arr[4];
                $ret['pageButton'] = explode('-', $pageButton); //列表页面按钮

            //表名 | lock | edit:编辑:id,reply:回复:id | create_time-desc
            case ($c >= 4):
                $sort        = $arr[3];
                $ret['sort'] = explode('-', $sort); //排序 ['create_time','desc']

            //表名 | lock | edit:编辑:id,reply:回复:id
            case ($c >= 3):
                $ret['action'] = $arr[2]; //记录操作按钮

            //表名 | lock
            case ($c >= 2):
                $ret['property'] = trim($arr[1]); //属性，如:锁定
            //if (!is_numeric($showPage)) E('显示页面属性必须是数字');


            //表名
            case ($c >= 1) :
                $ret['title'] = trim($arr[0]); //表中文名

        }

        return $ret;


    }


}