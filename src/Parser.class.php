<?php

namespace Cgf;

/**
 * 解析cgf注释
 * Class parser
 * @package Cgf
 */
class parser
{

    const ADD = 8; //1000
    const EDIT = 4; //0100
    const LISTS = 2; //0010
    const SEARCH = 1; //0001

    public $defaultShowAllColumn=true;

    //获取字段类型及长度
    function getColumnType($type)
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





    function getColumnAttribute($columnInfo){
        $attribute = [];
        $attribute = $this->getAttributeByColumnDefinition($columnInfo);


        if(!empty($columnInfo['COLUMN_COMMENT'])){
            $commentAttribute = $this->parseComment($columnInfo['COLUMN_COMMENT']);
            return array_merge($attribute,$commentAttribute);
        }elseif($this->defaultShowAllColumn){
            $comment=$columnInfo['COLUMN_NAME']."|1111";
            $commentAttribute = $this->parseComment($comment);
            return array_merge($attribute,$commentAttribute);
        }

        return $attribute;

    }


    function getAttributeByColumnDefinition($columnInfo){

        $autoHiddenPrimaryKey = true;
        $inputAttribute = [];
        $inputAttribute['name'] = $columnInfo['COLUMN_NAME'];
        $type = strtoupper($columnInfo['DATA_TYPE']);
        if ($columnInfo['COLUMN_KEY'] == 'PRI' && $autoHiddenPrimaryKey ){ //主键设为隐藏
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
                $inputAttribute['row'] = 10;
            }
        } elseif (in_array($type, ["BLOB", "TEXT", "MEDIUMBLOB", "MEDIUMTEXT", "LONGBLOB", "LONGTEXT"])) {
            $inputAttribute['type'] = "textarea";
            $inputAttribute['row'] = 10;
        } else {
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 30;
        }

        return $inputAttribute;
    }

//根据一个字段信息创建一个表单项
    function createFormRow($columnInfo)
    {

        $cnName = empty($commentInfo['name']) ? $columnInfo['COLUMN_NAME'] : $commentInfo['name'];
        $name = $columnInfo['COLUMN_NAME'];
        $inputStr = "";
        $confStr = "";


        $validate = $this->getFieldJsValidateRules($commentInfo);
        if ($columnInfo['COLUMN_KEY'] == "PRI" && $this->page == 'edit') {
            $inputAttribute['type'] = "hidden";
        }

        $this->hidden = 0; //不是隐藏元素

        if (!empty($commentInfo['htmlType'])) { //字段指定了类型
            $commentInfo['htmlType'] = strtolower($commentInfo['htmlType']);

            if ($commentInfo['htmlType'] == 'password') {
                $inputStr .= "<input {$validate}  type=\"password\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
            } elseif ($commentInfo['htmlType'] == 'hidden') {
                $inputStr .= "<input  type=\"hidden\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
                $this->hidden = 1;
            } elseif ($commentInfo['htmlType'] == 'datepicker') {
                $inputStr = '<div class="input-group date" data-provide="datepicker"> 
                                <input {$validate}  type="text" id="' . $name . '"  name="' . $name . '" class="form-control" value="' . '{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}' . '" >
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>';
                $inputStr .= ' <script>
                                    $(".date").datepicker({
                                    language:"zh-CN",
                                    format: "yyyy-mm-dd ",
                                    autoclose:true
                                });
                            </script>';


            } elseif ($commentInfo['htmlType'] == 'datepicker_range') {

                /*
                 <input type="text" id="test1">

                 * */
                $inputStr = '<div class="input-group date" > 
                                <input {$validate}  type="text" id="' . $name . '"  name="' . $name . '" class="form-control" value="' . '{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}' . '" >
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>';

                $inputStr .= ' <script>
                                laydate.render({
                                    elem: "#' . $name . '",
                                    range: true
                                });
                            </script>';


            } elseif ($commentInfo['htmlType'] == 'editor') {
                $inputStr .= "<html:editor id=\"editor\" name=\"$name\" type=\"kindeditor\" style=\"width:680px;height:300px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>"; //
            } elseif ($commentInfo['htmlType'] == 'file') {
                $inputStr .= "<input class=\"avatar-input\" id=\"$name\"  name=\"$name\" type=\"file\">" . '<span>' . '{$vo[' . $name . '] }' . ' </span>';
                $this->haveUpload = true;
            } elseif ($commentInfo['htmlType'] == 'img') {
                $inputStr .= "<input onchange=\"previewImg(this,'{$name}')\" class=\"avatar-input\" id=\"$name\"  name=\"$name\" type=\"file\">";
                $inputStr .= "";
                $inputStr .= "";

                $inputStr .= "<div id='preview_img_{$name}'>
                                <notempty name=\"vo['$name']\">
                                <img src=" . '"{$vo.' . $name . '|img}"' . " width='100' />
                                 <else />
                                 <img src=" . '"{$Think.config.DEFAULT_IMG}"' . " width='100' />
                                 </notempty>
                                 </div>";
                $inputStr .= "";
                //$this->haveImg = true;
                $this->haveUpload = true;
            } elseif ($commentInfo['htmlType'] == 'textarea') {
                $inputStr .= "<textarea rows=\"5\" {$validate}  class=\"form-control\" name=\"$name\"  id=\"$name\">" . '{$vo.' . $name . '}' . "</textarea>";
            }
//var_dump($commentInfo);

            if ($commentInfo['options']) {
                $this->options[$columnInfo['COLUMN_NAME']] = var_export($commentInfo['options'], 1);
                $this->arrOptions[$columnInfo['COLUMN_NAME']] = $commentInfo['options'];
                if ($commentInfo['htmlType'] == "select") {

                    $first = "";
                    if ($this->page == 'search') {
                        $first = 'first="请选择"';
                        $this->assign("{$name}_selected", I($name));

                    }

                    if ($this->page == 'edit') {
                        $this->assign("{$columnInfo['COLUMN_NAME']}_selected", $this->data[$columnInfo['COLUMN_NAME']]);
                    }
                    $inputStr .= "<html:select  $first options='opt_{$name}' selected='{$name}_selected' name=\"{$name}\" />";

                } elseif ($commentInfo['htmlType'] == "radio") {

                    $first = "";
                    if ($this->page == 'search') {
                        $first = 'first="请选择"';
                        $this->assign("{$name}_selected", I($name));
                    }

                    if ($this->page == 'edit') {
                        $this->assign("{$columnInfo['COLUMN_NAME']}_selected", $this->data[$columnInfo['COLUMN_NAME']]);
                    }

                    $inputStr .= "<html:radio radios='opt_{$name}' checked='{$name}_selected' name='{$name}' separator='&nbsp;&nbsp;' />";

                } elseif ($commentInfo['htmlType'] == "checkbox") {
                    if ($this->page == 'edit') {
                        $this->assign("{$name}_selected", $this->data[$name]);
                    }
                    $inputStr .= "<html:checkbox checkboxes='opt_{$name}' checked='{$name}_selected' name='{$name}' />";

                    /* foreach ($commentInfo['options'] as $value => $text) {
                         $inputStr .= "  <input {$validate}  name=\"{$name}\" id=\"{$name}\"  type=\"checkbox\" value=\"$value\">{$text} ";
                     }*/
                    //$inputStr = "<input name=\"$name\" type=\"text\" id=\"$name\" size=\"{$inputAttribute['size']}\" />";
                }
            }


        } else { //没有指定类型，取默认分析出的类型
            if ($inputAttribute['type'] == "text") {
                $inputStr .= "<input {$validate} class=\"form-control\" name=\"$name\" type=\"text\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}"' . " />";
                //$inputStr .= "<input class=\"form-control\" name=\"$name\" type=\"text\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo[$name]}"' . " />";
            } elseif ($inputAttribute['type'] == 'hidden') {
                $inputStr .= "<input  type=\"hidden\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
                $this->hidden = 1;
            } elseif ($inputAttribute['type'] == "textarea") {
                $inputStr .= "<textarea {$validate}  class=\"form-control\" name=\"$name\" style=\"width:800px;height:400px;visibility:hidden;\" id=\"$name\">" . '{$vo.' . $name . '}' . "</textarea>";
            } elseif (in_array($inputAttribute['type'], ['date', 'time'])) {
                $inputStr .= '<div class="input-group date" id="datetimepicker_' . $name . '">
                    <input type="text" id="' . $name . '" name="' . $name . '" class="form-control" value= ' . '"{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}"' . ' />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>';

                if ($inputAttribute['type'] == 'date') {
                    $inputStr .= '<script>
                    $(function () {
                        $("#datetimepicker_' . $name . '").datetimepicker({
                                    format: "YYYY-MM-DD ",
                                });
                     });
                    </script>';

                } else {
                    $inputStr .= '<script>
                    $(function () {
                        $("#datetimepicker_' . $name . '").datetimepicker({
                                    format: "YYYY-MM-DD HH:mm:ss",
                                });
                     });
                    </script>';
                }

                /* $inputStr = '<div class="input-group date" data-provide="datepicker">
                                 <input {$validate}  type="text" id="'.$name.'"  name="'.$name.'" class="form-control" value="'. '{$vo.' . $name . '}' .'" >
                                 <div class="input-group-addon">
                                     <span class="glyphicon glyphicon-th"></span>
                                 </div>
                             </div>';

                 if($inputAttribute['type'] == 'date'){
                     $inputStr .= ' <script>
                                     $(".date").datepicker({
                                     language:"zh-CN",
                                     format: "yyyy-mm-dd ",
                                     autoclose:true
                                 });
                             </script>';
                 }else {
                     $inputStr .= ' <script>
                                     $(".date").datepicker({
                                     language:"zh-CN",
                                     format: "yyyy-mm-dd 时间控件",
                                     autoclose:true
                                 });
                             </script>';

                 }*/


            }
        }
        //exit('xxx222');

        $tips = $commentInfo['tips'];
        $this->name = $name;
        $this->cnName = $cnName;
        $this->inputStr = $inputStr;
        $this->tips = $tips;
        $this->required = (strpos($commentInfo['checkType'], 'require') !== false);
        if ($this->page == 'search') {
            $r = $this->fetch("tpl_search_row");
        } else {
            $r = $this->fetch("tpl_row");
        }
        return $r;

    }

    function getShowPage($showPage, $module)
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
     * 解析注释获取name和选项
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
    function parseComment($comment)
    {
        //先提取正则，避免正则里的特殊符号污染后面处理
        $reg = '/<<(.+)>>/';
        $validateReg = '';
        preg_match($reg, $comment, $match);
        if (!empty($match[1])) {
            $validateReg = $match[1];
            $comment = preg_replace($reg, 'reg', $comment);
        }

        //$comment = '状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中';
        //$id=$name='status';
        $ret = [];
        //$comment = '状态|0:禁用,1:正常,2:待审核';
        //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
        $arr = explode("|", $comment);
        $arr = array_map('trim', $arr);
        //array_walk($arr,function (&$v){ $v = trim($v); });
        $c = count($arr); //parseVarFunction
        switch (true) {
            //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中 | implode=",",###
            case ($c >= 5):
                $function = $arr[4];

            //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
            case ($c >= 4):
                $options = $arr[3];

            //状态-select-禁用则不能访问 | 7 | require
            case ($c >= 3):
                $checkType = $arr[2];

            //状态-select-禁用则不能访问 | 7
            case ($c >= 2):
                $showPage = trim($arr[1]);
            //if (!is_numeric($showPage)) E('显示页面属性必须是数字');


            //状态-select-禁用则不能访问
            case ($c >= 1) :
                $title = trim($arr[0]);

        }

        /*       var_dump($title);
               var_dump($showPage);
               var_dump($checkType);
               var_dump($options);*/

        $arrTitle = explode("-", $title);
        $c = count($arrTitle);
        switch ($c) {
            //状态-select-禁用则不能访问
            case ($c >= 4):
                $flag = $arrTitle[3];

            //状态-select-禁用则不能访问
            case ($c >= 3):
                $tips = $arrTitle[2];

            //状态-select
            case ($c >= 2):
                $type = $arrTitle[1];

            //状态
            case ($c >= 1) :
                $zh = $arrTitle[0];


        }

        /* var_dump($name);
         var_dump($htmlType);
         var_dump($tips);*/


        //显示页面分析


        $allModule = explode('-', $showPage);
        if (count($allModule) == 3) {
            $admin = $allModule[0];
            $user = $allModule[1];
            $home = $allModule[2];
            //var_dump($allModule,$admin,$user);
        } elseif (count($allModule) == 2) {
            $admin = $allModule[0];
            $user = $allModule[1];
            $home = $admin;
        } else {
            $admin = $allModule[0];
            $user = $admin;
            $home = $admin;
        }


        $arrShowPages = [];
        $arrShowPages['admin'] = $this->getShowPage($admin, 'admin');
        $arrShowPages['user'] = $this->getShowPage($user, 'user');
        $arrShowPages['home'] = $this->getShowPage($home, 'home');

        //echo $comment;
        //var_dump($arrShowPages);exit;

        //选项分析
        $rawOption = $options; //原始option值
        if (!empty($options)) {
            if (strpos($options, 'function') !== false) {
                $func = explode('=', $options)[1];
                /*$r = $func();
                $options = [];
                foreach ($r as $k => $v) {
                    $options[$v['id']] = $v['title'];
                }*/
                $options = ['function'=>$func];
                //var_dump($options);
                //exit('x');
            } else {
                $arrOptions = [];
                $items = explode(",", $options);
                $options = [];
                foreach ($items as $item) {
                    list($value, $text) = explode(':', $item);
                    $value = trim($value);
                    $text = trim($text);
                    $arrOptions[$value] = "$text";
                }
                $options = $arrOptions;
            }
        }

        //验证规则分析
        if (!empty($checkType)) {
            $arrRules = [];
            $allRules = explode("-", $checkType);
            foreach ($allRules as $k => $v) {
                $ruleInfo = explode(':', $v);
                $temp = [];
                $temp['type'] = $ruleInfo[0];
                if ($ruleInfo[0] == 'reg') {
                    $temp['reg'] = $validateReg;
                }

                if ($ruleInfo[1]) {
                    $temp['msg'] = $ruleInfo[1];
                }
                $arrRules[] = $temp;
            }

        }


        //$ret['zh']
        $ret = compact('zh', 'type', 'tips', 'flag', 'showPage', 'arrShowPages', 'checkType', 'arrRules', 'options', 'rawOption');

        if(!empty($function)){
            $arrFunction = explode('=',$function); //处理 tpl_function=img()格式定义

            if(!empty($arrFunction[1])){
                $ret[$arrFunction[0]] = $arrFunction[1];
            }else{ //默认格式
                //$$functionKey = $function;
                $ret['function'] = $function;
            }
        }
        return $ret;
    }


    function parseTableComment($comment){
        //$comment="问题反馈|xx|reply:回复";

        $pageButton=[];

        $arr = explode("|", $comment);
        $arr = array_map('trim', $arr);
        //array_walk($arr,function (&$v){ $v = trim($v); });
        $c = count($arr); //parseVarFunction
        switch (true) {
            //表名 | lock | edit:编辑:id,reply:回复:id | create_time-desc | export-showMenu | function_name
            case ($c >= 5):
                $function = $arr[5];

            //表名 | lock | edit:编辑:id,reply:回复:id | create_time-desc | export-showMenu
            case ($c >= 5):
                $pageButton = $arr[4];
                $pageButton = explode('-',$pageButton); //列表页面按钮

            //表名 | lock | edit:编辑:id,reply:回复:id | create_time-desc
            case ($c >= 4):
                $sort = $arr[3];
                $sort = explode('-',$sort); //排序 ['create_time','desc']

            //表名 | lock | edit:编辑:id,reply:回复:id
            case ($c >= 3):
                $action = $arr[2]; //记录操作按钮

            //表名 | lock
            case ($c >= 2):
                $property = trim($arr[1]); //属性，如:锁定
            //if (!is_numeric($showPage)) E('显示页面属性必须是数字');


            //表名
            case ($c >= 1) :
                $title = trim($arr[0]); //表中文名

        }

        $ret = compact('title','property','action','sort','pageButton','function');
        return $ret;


    }



}