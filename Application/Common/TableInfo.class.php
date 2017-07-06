<?php
namespace Common;
use Think\Controller;

class TableInfo extends Controller
{

    protected $options = []; //存储数组序列化后的字符串格式
    protected $arrOptions = []; //存储数组格式
    public $page = null;
    public $data = null; //记录的值

    /**
     * TableInfo constructor.
     * @param $page 要生成的页面 add,edit,lists
     */
    function __construct($page = '')
    {
        parent::__construct();
        $this->page = $page;
    }

//获取表名列表
    static function getTableNameList()
    {
        $dbType = C('DB_TYPE');
        $Model = M(); // 实例化一个model对象 没有对应任何数据表
        if (in_array($dbType, array('mysql', 'mysqli'))) {
            $dbName = C('DB_NAME');
            $result = Array();
            $tempArray = $Model->query("select table_name from information_schema.tables where table_schema='" . $dbName . "' and table_type='base table'");
            foreach ($tempArray as $temp) {
                $result[] = $temp['table_name'];
            }
            return $result;
        } else { //sqlite
            $result = Array();
            $tempArray = $Model->query("select * from sqlite_master where type='table' order by name");
            foreach ($tempArray as $temp) {
                $result[] = $temp['name'];
            }
            return $result;
        }
        $this->error('数据库类型不支持');
    }

//读取项目目录下的文件夹，供用户选择哪个才是module目录
    static function getModuleNameList()
    {
        $ignoreList = Array("Common", "Runtime", "TPH");
        $allFileList = getDirList(APP_PATH);
        return array_diff($allFileList, $ignoreList);
    }

//获取列名列表
    static function getTableInfoArray($tableName)
    {
        $dbType = C('DB_TYPE');
        $Model = M(); // 实例化一个model对象 没有对应任何数据表
        if ($dbType == 'mysql') {
            $dbName = C('DB_NAME');
            $result = $Model->query("select * from information_schema.columns where table_schema='" . $dbName . "' and table_name='" . '' .  C('DB_PREFIX') .$tableName . "'");
            return $result;
        } else { //sqlite
            $result = $Model->query("pragma table_info (" . C('DB_PREFIX') . $tableName . ")");
            return $result;
        }
        $this->error('数据库类型不支持');
    }


//根据数据库类型获取列名键
    static function getColumnNameKey()
    {
        $dbType = C('DB_TYPE');
        if ($dbType == 'mysql') {
            return MYSQL_COLUMN_NAME_KEY;
        } else {
            return SQLITE_COLUMN_NAME_KEY;
        }
    }

//仅获取目录列表
    static function getDirList($directory)
    {
        $files = array();
        try {
            $dir = new \DirectoryIterator($directory);
        } catch (Exception $e) {
            throw new Exception($directory . ' is not readable');
        }
        foreach ($dir as $file) {
            if ($file->isDot()) continue;
            if ($file->isFile()) continue;
            $files[] = $file->getFileName();
        }
        return $files;
    }

//把带下划线的表名转换为驼峰命名（首字母大写）
    static function tableNameToModelName($tableName)
    {
        $tempArray = explode('_', $tableName);
        $result = "";
        for ($i = 0; $i < count($tempArray); $i++) {
            $result .= ucfirst($tempArray[$i]);
        }
        return $result;
    }

//把带下划线的列名转换为驼峰命名（首字母小写）
    static function columNameToVarName($columName)
    {
        $tempArray = explode('_', $columName);
        $result = "";
        for ($i = 0; $i < count($tempArray); $i++) {
            $result .= ucfirst($tempArray[$i]);
        }
        return lcfirst($result);
    }


    public function index()
    {
        $tableNameList = self::getTableNameList();
        //$this->tabText = $tableNameList;
        //$this->tabText = $tableNameList;
        $this->tabText = array_combine($tableNameList, $tableNameList);
        $this->display();

    }

    function preview()
    {
        $this->display("tpl_preview");
    }

    /**
     * @param $tableName 表名
     * @param string $page 生成什么页面
     * @return string|void
     */
    function generateForm($tableName)
    {
        empty($tableName) && $tableName = I('tableName');
        $columnNameKey = strtoupper(self::getColumnNameKey());
        $str = '';
        $selectedFields = I('tableFields');
        if (empty($tableName)) {
            $this->generateAll();
            return;
        } else {
            $allFields = self::getTableInfoArray($tableName);
        }
        $str .= '<form class="form-horizontal" role="form"  method="post" action="__URL__/save/">';
        foreach ($allFields as $columnInfo) {
            if (!empty($selectedFields) && !in_array($columnInfo['COLUMN_NAME'], $selectedFields)) continue;
            //if (!I('hasId') && $columnInfo['COLUMN_KEY'] == "PRI") continue;
            $str .= $this->createFormRow($columnInfo);
            //$str .= '<option value="'.$columnInfo[$columnNameKey].'" >'.$columnInfo[$columnNameKey]."</option>\r\n";
        }

        $this->allRows = $str;
        $r = $this->fetch("tpl_form");
//var_dump($r);exit;
        foreach ($this->arrOptions as $k => $v) {
            $this->assign('opt_' . $k, $v);
        }
        $r = $this->fetch("", $r);
        return $r;
        echo $r;

        /* $str .= '<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">保存</button>
    </div>
  </div>'; */
        //$str .='</form>';
        //echo $str;

        /* echo "\n\n\n\n";
         foreach ($this->options as $k => $v){
             echo '"'.$tableName.'_'.$k.'"',"=>",$v,"\n\n";
         }*/

    }

    /**
     * 生成列表模板
     * @param $tableName
     * @return string
     */
    function generateLists($tableName){
        $fields = $this->createListFields($tableName);
        $this->f_list = $fields;

        $fieldsKey = 'tpl_fields.'.strtolower($tableName);
        $tpl_fields = C($fieldsKey);
        if(empty($tpl_fields['f_action'])){
            $this->f_action = C('f_action');
        }else{
            $this->f_action = $tpl_fields['f_action'];
        }



        //搜索框生成
        $this->page = 'search';
        $htmlSearch = $this->generateSearch($tableName);
        $this->control = '__CONTROLLER__';
        $this->htmlSearch = $htmlSearch;

        $str = $this->fetch("tpl_list");
        $r = $this->fetch("", $str);
        return $r;
        //file_put_contents("$path/index.html", $str);
    }

    /**
     * @param $tableName  生成搜索模板
     */
    function generateSearch($tableName){
        $allFields = self::getTableInfoArray($tableName);
        foreach ($allFields as $columnInfo) {
            if (!empty($selectedFields) && !in_array($columnInfo['COLUMN_NAME'], $selectedFields)) continue;
            //if (!I('hasId') && $columnInfo['COLUMN_KEY'] == "PRI") continue;
            $str .= $this->createFormRow($columnInfo);
        }

        foreach ($this->arrOptions as $k => $v) {
            $this->assign('opt_' . $k, $v);
        }
        $r = $this->fetch("", $str);
        return $r;

    }

    //生成所有表的form,control,model
    function generateAll()
    {
        $prefix = C("DB_PREFIX");
        $tableNameList = I('tableName');
        if (empty($tableNameList)) {
            $tableNameList = self::getTableNameList();
        }
        foreach ($tableNameList as $k => $tableName) {

            $this->generateView($tableName);

            $className = ucfirst(str_replace($prefix, '', $tableName));
            $this->generateController($className);
            $this->generateModel($className);
        }

        echo "文件已经生成到: {$this->savePath}";


    }


    protected $savePath = "./data";

    /**
     * 生成controller
     * @param $className
     */
    function generateController($className)
    {
        $tplPath = T('tpl_controller');
        $tpl = file_get_contents($tplPath);
        $tpl = str_replace('{$className}', $className, $tpl);
        $className = parse_name($className, 1);
        $path = $this->savePath . "/Controller";
        if (!file_exists($path)) mkdir($path, 0777, true);
        file_put_contents("{$path}/{$className}Controller.class.php", $tpl);
    }

    /**
     * 生成model
     * @param $className
     */
    function generateModel($className)
    {
        $tplPath = T('tpl_model');
        $tpl = file_get_contents($tplPath);
        $tpl = str_replace('{$className}', $className, $tpl);
        $className = parse_name($className, 1);
        $path = $this->savePath . "/Model";
        if (!file_exists($path)) mkdir("$path", 0777, true);
        file_put_contents("{$path}/{$className}Model.class.php", $tpl);
    }

    /**
     * 生成view,添加表单，和列表
     * @param $tableName
     */
    function generateView($tableName)
    {

        $tableInfoArray = getTableInfoArray($tableName);
        $columnNameKey = strtoupper(getColumnNameKey());
        $str = '';

        //生成添加表单
        $str .= '<form class="form-horizontal" role="form"  method="post" action="__URL__/save/">';
        foreach ($tableInfoArray as $columnInfo) {
            //var_dump($columnInfo);exit;
            $str .= $this->createFormRow($columnInfo);
            //$str .= '<option value="'.$columnInfo[$columnNameKey].'" >'.$columnInfo[$columnNameKey]."</option>\r\n";
        }

        $this->allRows = $str;
        $str = $this->fetch("tpl_form");

        $prefix = C("DB_PREFIX");
        $className = ucfirst(str_replace($prefix, '', $tableName));
        $className = parse_name($className, 1);
        $path = $this->savePath . "/View/$className/";
        if (!file_exists($path)) mkdir("$path", 0777, true);
        file_put_contents("$path/add.html", $str);


        //生成列表模板
        //$tplPath = T('tpl_list');
        //$tpl = file_get_contents($tplPath);
        $fields = $this->createListFields($tableInfoArray);
        $this->fields = $fields;
        $this->control = '__CONTROLLER__';
        $str = $this->fetch("tpl_list");
        file_put_contents("$path/index.html", $str);
    }

    /**
     * 创建列表字段
     * @param $tableName 表名
     * @return string 
     */
    function createListFields($tableName)
    {
        $allFields = self::getTableInfoArray($tableName);
        $fields = [];
        foreach ($allFields as $columnInfo) {
            $commentInfo = $this->parseComment($columnInfo['COLUMN_COMMENT']);
            if (!in_array($this->page, $commentInfo['arrShowPages'])) { //字段不显示，返回空
                continue;
            }
         //   var_dump($commentInfo);
            $cnName = empty($commentInfo['name']) ? $columnInfo['COLUMN_NAME'] : $commentInfo['name'];
            $name = $columnInfo['COLUMN_NAME'];
            if($commentInfo['options']){ //有选项，则在列表页调用函数根据key找到对应的value
                //var_dump($commentInfo['options']);exit;
                C($name,$commentInfo['options']);
                $functionName = "|optionsValue='$name'";//.ucfirst($name);
            }else{
                $functionName = '';
            }
            $fields[] = "$name{$functionName}:$cnName";
        }
        return implode(',', $fields);
    }


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

    const ADD = 8; //1000
    const EDIT = 4; //0100
    const LISTS = 2; //0010
    const SEARCH = 1; //0001

    /**
     * 解析注释获取name和选项
     * 格式说明：
     * 以 |-，之类的做分隔
     * 注释标题 - htm控件类型 - 提示 | 校验类型 | 展现页面 | 选项
     *
     * 注释标题: 一般是字段的中文标题，form表单的label
     * html控件类型: select,checkbox,input,textare等
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
     * 选项： 选项1:选项1值，选项2：缺项2值
     * 状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
     *
     *
     * 验证正则支持，这个比较蛋疼，由于正则里可以使用任意字符，与我们使用的分隔符有冲突，使用特殊符号将正则括起来
     * <<正则表达式>>,在此只允许验证里出现，否则就不知道<< >>括起来的什么了，注意目前<<>>不支持转义，如果正则表达式里还有<< >> 那就只能over了。
     *  首先将正则提取出来，再进行分隔
     *
     * @param $comment
     * @return array
     */
    function parseComment($comment)
    {
        //先提取正则，避免正则里的特殊符号污染后面处理
        $reg = '/<<(.+)>>/';
        $validateReg = '';
        preg_match($reg,$comment,$match);
        if(!empty($match[1])){
            $validateReg = $match[1];
            $comment = preg_replace($reg,'reg',$comment);
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
                $auto = $arr[4];

            //状态-select-禁用则不能访问 | 7 | require | 0:禁用,1:正常,2:审核中
            case ($c >= 4):
                $options = $arr[3];

            //状态-select-禁用则不能访问 | 7 | require
            case ($c >= 3):
                $checkType = $arr[2];

            //状态-select-禁用则不能访问 | 7
            case ($c >= 2):
                $showPage = trim($arr[1]);
                if (!is_numeric($showPage)) E('显示页面属性必须是数字');


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
            case ($c >= 3):
                $tips = $arrTitle[2];

            //状态-select
            case ($c >= 2):
                $htmlType = $arrTitle[1];

            //状态
            case ($c >= 1) :
                $name = $arrTitle[0];


        }
        /* var_dump($name);
         var_dump($htmlType);
         var_dump($tips);*/


        //显示页面分析

        if (strlen($showPage)<1 || $showPage == null) $showPage = 15;

        if(strlen($showPage) == 4) $showPage = bindec($showPage);
        $arrShowPages = [];
        if ($showPage & self::ADD) $arrShowPages[] = 'add';
        if ($showPage & self::EDIT) $arrShowPages[] = 'edit';
        if ($showPage & self::LISTS) $arrShowPages[] = 'list';
        if ($showPage & self::SEARCH) $arrShowPages[] = 'search';

        //选项分析
        if (!empty($options)) {
            $arrOptions = [];
            $items = explode(",", $options);
            $options = [];
            foreach ($items as $item) {
                list($value, $text) = explode(':', $item);
                $arrOptions[$value] = "$text";
            }
            $options = $arrOptions;
        }

        //验证规则分析
        if(!empty($checkType)){
            $arrRules = [];
            $allRules = explode("-", $checkType);
            foreach ($allRules as $k => $v){
                $ruleInfo = explode(':',$v);
                $temp = [];
                $temp['type'] = $ruleInfo[0];
                if($ruleInfo[0] == 'reg'){
                    $temp['reg'] = $validateReg;
                }

                if($ruleInfo[1]){
                    $temp['msg']=$ruleInfo[1];
                }
                $arrRules[] = $temp;
            }

        }

        $ret = compact('name', 'htmlType', 'tips', 'showPage', 'arrShowPages', 'checkType','arrRules', 'options','auto');
        return $ret;
    }

    //根据一个字段信息创建一个表单项
    function createFormRow($columnInfo)
    {
        $inputAttribute = [];
        //$typeInfo = $this->getColumnType($columnInfo['COLUMN_TYPE']);
        $type = strtoupper($columnInfo['DATA_TYPE']);
        if (in_array($type, ["TINYINT", "SMALLINT", "MEDIUMINT", "INT", "BIGINT", "FLOAT", "DOUBLE", "DECIMAL"])) { //数字类型
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 10;
        } elseif (in_array($type, ["DATE",  "YEAR"])) { //日期类型
            $inputAttribute['type'] = "date";
        }elseif (in_array($type,["TIME","DATETIME", "TIMESTAMP" ])){
            $inputAttribute['type'] = "time";
        }elseif (in_array($type, ["CHAR", "VARCHAR", "TINYBLOB", "TINYTEXT"])) { //小文本
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 30;
            if ($type == "varchar" && $columnInfo['size'] > 255) { //大文本域
                $inputAttribute['type'] = "textare";
                $inputAttribute['row'] = 10;
            }
        } elseif (in_array($type, ["BLOB", "TEXT", "MEDIUMBLOB", "MEDIUMTEXT", "LONGBLOB", "LONGTEXT"])) {
            $inputAttribute['type'] = "textare";
            $inputAttribute['row'] = 10;
        } else {
            $inputAttribute['type'] = "text";
            $inputAttribute['size'] = 30;
        }

        $commentInfo = $this->parseComment($columnInfo['COLUMN_COMMENT']);
        if (!in_array($this->page, $commentInfo['arrShowPages'])) { //字段不显示，返回空
            return '';
        }
        if (!empty($commentInfo['options'])) {
            $inputAttribute['type'] = "text";
        }

        $cnName = empty($commentInfo['name']) ? $columnInfo['COLUMN_NAME'] : $commentInfo['name'];
        $name = $columnInfo['COLUMN_NAME'];
        $inputStr = "";
        $confStr = "";


        $validate = $this->getFieldJsValidateRules($commentInfo);
        if($columnInfo['COLUMN_KEY'] == "PRI" && $this->page == 'edit'){
            $inputAttribute['type'] = "hidden";
        }

        $this->hidden = 0; //不是隐藏元素

        if (!empty($commentInfo['htmlType'])) { //字段指定了类型
            $commentInfo['htmlType'] = strtolower($commentInfo['htmlType']);

            if($commentInfo['htmlType'] == 'password'){
                $inputStr .= "<input {$validate}  type=\"password\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
            }elseif ($commentInfo['htmlType'] == 'hidden'){
                $inputStr .= "<input  type=\"hidden\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
                $this->hidden = 1;
            }elseif($commentInfo['htmlType'] == 'datepicker'){
                $inputStr = '<div class="input-group date" data-provide="datepicker"> 
                                <input {$validate}  type="text" id="'.$name.'"  name="'.$name.'" class="form-control" value="'. '{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}'.'" >
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



            }elseif($commentInfo['htmlType'] == 'editor'){
                $inputStr .= "<html:editor id=\"editor\" name=\"$name\" type=\"kindeditor\" style=\"width:680px;height:300px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>"; //
            }


            if ($commentInfo['options']) {
                $this->options[$columnInfo['COLUMN_NAME']] = var_export($commentInfo['options'], 1);
                $this->arrOptions[$columnInfo['COLUMN_NAME']] = $commentInfo['options'];
                if ($commentInfo['htmlType'] == "select") {

                    $first =  "";
                    if($this->page == 'search'){
                        $first = 'first="请选择"';
                        $this->assign("{$name}_selected", I($name));
                    }

                    if($this->page == 'edit'){
                        $this->assign("{$columnInfo['COLUMN_NAME']}_selected", $this->data[$columnInfo['COLUMN_NAME']]);
                    }
                    $inputStr .= "<html:select  $first options='opt_{$name}' selected='{$name}_selected' name=\"{$name}\" />";
                    /*$inputStr .= " <select name=\"select\" id=\"select\">";
                    foreach($commentInfo['options'] as $value => $text){
                        $inputStr.="<option value=\"{$value}\">$text</option>";
                    }
                    $inputStr .= "</select>";*/

                } elseif ($commentInfo['htmlType'] == "radio") {

                    $first =  "";
                    if($this->page == 'search'){
                        $first = 'first="请选择"';
                        $this->assign("{$name}_selected", I($name));
                    }

                    if($this->page == 'edit'){
                        $this->assign("{$columnInfo['COLUMN_NAME']}_selected", $this->data[$columnInfo['COLUMN_NAME']]);
                    }
                    //$inputStr .= "<html:select  $first options='opt_{$name}' selected='{$name}_selected' name=\"{$name}\" />";
                    $inputStr .= "<html:checkbox checkboxes='opt_{$name}' checked='{$name}_selected' name='{$name}' />";


                } elseif ($commentInfo['htmlType'] == "checkbox") {
                    foreach ($commentInfo['options'] as $value => $text) {
                        $inputStr .= "  <input {$validate}  name=\"select\" id=\"select\"  type=\"checkbox\" value=\"$value\">{$text} |";
                    }

                    //$inputStr = "<input name=\"$name\" type=\"text\" id=\"$name\" size=\"{$inputAttribute['size']}\" />";
                }
            }


        } else { //没有指定类型，取默认分析出的类型
            if ($inputAttribute['type'] == "text") {
                $inputStr .= "<input {$validate} class=\"form-control\" name=\"$name\" type=\"text\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}"' . " />";
                //$inputStr .= "<input class=\"form-control\" name=\"$name\" type=\"text\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo[$name]}"' . " />";
            }elseif ($inputAttribute['type'] == 'hidden'){
                $inputStr .= "<input  type=\"hidden\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$inputAttribute['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
                $this->hidden = 1;
            }elseif ($inputAttribute['type'] == "textare") {
                $inputStr .= "<textarea {$validate}  class=\"form-control\" name=\"$name\" style=\"width:800px;height:400px;visibility:hidden;\" id=\"$name\"></textarea>";
            } elseif ( in_array($inputAttribute['type'],['date','time']) ){
                $inputStr .= '<div class="input-group date" id="datetimepicker1">
                    <input type="text" id="'.$name.'" name="'.$name.'" class="form-control" value= '. '"{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}"'.' />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>';

                if($inputAttribute['type'] == 'date') {
                    $inputStr .= '<script>
                    $(function () {
                        $("#datetimepicker1").datetimepicker({
                                    format: "YYYY-MM-DD ",
                                });
                     });
                    </script>';

                }else{
                    $inputStr .= '<script>
                    $(function () {
                        $("#datetimepicker1").datetimepicker({
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


        $tips = $commentInfo['tips'];
        $this->name = $name;
        $this->cnName = $cnName;
        $this->inputStr = $inputStr;
        $this->tips = $tips;
        $this->required = $commentInfo['checkType'] == 'require';
        if($this->page == 'search'){
            $r = $this->fetch("tpl_search_row");
        }else{
            $r = $this->fetch("tpl_row");
        }
        return $r;

    }

    public function generateCreatFormCode()
    {
        $templateFilePath = MODULE_PATH . "Template/View/formCode.html";
        $formMethod = I('formMethod');
        $formAction = I('formAction');
        $this->assign('formMethod', $formMethod);
        $this->assign('formAction', $formAction);
        $resultCode = $this->fetch($templateFilePath);
        return $resultCode;
    }

    public function creatForm()
    {
        echo $this->generateCreatFormCode();
    }

    public function loadField()
    {
        $tableName = I('tableName');
        if (is_array($tableName)) {
            $tableName = $tableName[count($tableName) - 1];
        }
        $tableInfoArray = getTableInfoArray($tableName);
        $columnNameKey = strtoupper(getColumnNameKey());
        $str = '';
        foreach ($tableInfoArray as $tableInfo) {
            $str .= '<option value="' . $tableInfo[$columnNameKey] . '" >' . $tableInfo[$columnNameKey] . "</option>\r\n";
        }
        echo $str;
    }

    /**
     * @param $tableName 获取自动完成规则
     */
    function getAutoComplete($tableName){
        $rules = [];
        $allFields = self::getTableInfoArray($tableName);
        $template = new  \Think\Template();
        $autos = [];
        foreach ($allFields as $columnInfo) {
            $commentInfo = $this->parseComment($columnInfo['COLUMN_COMMENT']);
            $columnName =  $columnInfo['COLUMN_NAME'];
            if(empty($commentInfo['auto'])) continue;

            //方式一： 需要启用eval来执行，这种方式的优势就是能直接使用php内置函数,有些简单的操作就不用加自定义function
            $auto = $commentInfo['auto'];
            $name = "$$columnName";
            $r = $template->parseVarFunction($name,[$auto]);
            //var_dump($r);  输出 string(16) "implode(",",$id)"
            // 这样就需要启用eval,然后eval函数执行代码，不像解析模板那些，直接输出到.php文件中，然后运行时自然就能执行了。


            //方式二: 自己修改下解析，将函数在此时就运行，得到结果返回
            $paramValue = I($columnName);
            //$paramValue = [1,2,3];
            $result = $this->parseAutoFunction($paramValue,$auto);
            //修改 post,get值
            $autos[$columnName] = $_POST[$columnName] = $_GET[$columnName] = $result;

            //方式三: 这个写法比较简单，|函数名，但需要自己增加自定义函数，且只能传1个默认参数，就是当前字段页面上传过来的值
                /*if($v['type'] == 'unique'){
                    $rules[] = array($columnName,'',$commentInfo['name'].'名称已经存在！',0,'unique',1);
                }*/

        }
        return $autos;
    }

    /**
     * 解析自动完成函数
     * 格式 |function=arg1,arg2
     * @access public
     * @param string $paramValue 变量名
     * @param array $autoFunction  函数列表
     * @return string
     */
    public function parseAutoFunction($paramValue, $autoFunction)
    {
        //取得模板禁止使用函数列表
        /*
        $functionName 值为 implode='\,',###
         转为  implode='[comma]',###
         将等号后面的参数转为数组形式
        $params = array(
            0 => [comma],
            1 => ###
        );

        //找到### ,用$paramValue替换变成
        $params = array(
            0 => [comma],
            1 => $paramValue
        );

        将$params 转为字符串 $params = "[comma],$paramValue"
        再将 [comma] 还原为 ,
        最终执行代码为： implode(',',$paramValue)

        */

        $template_deny_funs = explode(',', C('TMPL_DENY_FUNC_LIST'));
            $args = explode('=', $autoFunction, 2);
            //模板函数过滤
            $fun = trim($args[0]);

            switch ($fun) {
                case 'default': // 特殊模板函数
                    $paramValue = '(isset(' . $paramValue . ') && (' . $paramValue . ' !== ""))?(' . $paramValue . '):' . $args[1];
                    break;
                default: // 通用模板函数
                    if (!in_array($fun, $template_deny_funs)) {
                        if (isset($args[1])) {
                            if (strstr($args[1], '###')) {
                                $args[1] = str_replace('\,','[comma]',$args[1]); // 将参数里的\, 转义的逗号先替换为特殊标识
                                $allParams = explode(',',$args[1]);
                                $index = array_search('###',$allParams);
                                $allParams[$index] = $paramValue;
                                foreach ($allParams as $k => &$v){
                                    if($k == $index) continue;
                                    $v = str_replace('[comma]',',',$v);
                                }
                                $result = call_user_func_array($fun,$allParams);
                            } else {
                                $result = $fun($paramValue,$args[1]);
                            }
                        } else if (!empty($args[0])) {
                            $result = $fun($paramValue);
                        }
                    }
            }
        return $result;
    }

    /**
     * @param $table 获取表的验证规则
     */
    function getValidateRules($tableName)
    {
        $rules = [];
        $allFields = self::getTableInfoArray($tableName);
        foreach ($allFields as $columnInfo) {
            $commentInfo = $this->parseComment($columnInfo['COLUMN_COMMENT']);
            $columnName =  $columnInfo['COLUMN_NAME'];

            if(empty($commentInfo['arrRules'])) continue;
            $arrRules = $commentInfo['arrRules'];
            foreach ($arrRules as $k => $v){
                if($v['type'] == 'require'){
                    $rules[] = array($columnName,'require',$commentInfo['name'].'必须填写！');
                }
                if($v['type'] == 'unique'){
                    $rules[] = array($columnName,'',$commentInfo['name'].'名称已经存在！',0,'unique',1);
                }

                /*array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
                array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
                array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
                var_dump($checkType);exit;*/
            }
        }
        return $rules;
    }

    /**
     *
     */
    function getFieldJsValidateRules($commentInfo){
//var_dump($commentInfo);
        $strValidate = '';
        foreach ($commentInfo['arrRules'] as $k => $v){
            //datatype="*6-15" errormsg="密码范围在6~15位之间！"
            if($v['type'] == 'require'){
                if($v['msg']){
                    $strValidate .= 'nullmsg="'.$v['msg'].'"';
                }else{
                    $strValidate .= 'nullmsg="请填写信息"';
                }
            }

            if($v['type'] == 'reg'){
                //正则验证
                $strValidate .= 'datatype="'.$v['reg'].'" ';
                if($v['msg']){
                    $strValidate .= 'errormsg="'.$v['msg'].'"';
                }else{
                    $strValidate .= 'errormsg="正则验证不通过"';
                }
            }elseif($v['type'] == 'email'){
                $strValidate .= 'datatype="e"  ';
                if($v['msg']){
                    $strValidate .= 'errormsg="'.$v['msg'].'"';
                }else{
                    $strValidate .= 'errormsg="邮箱格式不正确"';
                }
            }

            //var_dump($v);
        }
        return $strValidate;
    }


}

class test
{
    public $t = null;

    function __construct()
    {
        $this->t = new TableInfo();
    }

    function parseComment()
    {
        $comment = '状态-select-禁用则不能访问 | 7 | reqiure | 0:禁用,1:正常,2:审核中';
        $this->t->parseComment($comment);
    }
}