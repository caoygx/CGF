<?php

namespace Cgf;

use Cgf\Form\BootstrapForm;
use Cgf\Validate\ThinkphpValidate;


/**
 * 实现join时需要表字段配合
 * 设计表时，关联字段的名称最好一致。
 * 关联字段尽量不重复
 * Class TableInfo
 * @package Common
 */
class Cgf
{

    public $definition;
    public $form;
    public $tableName;
    public $tableFullName;
    public $framework;
    public $tableInfo;
    public $validate;

    /**
     *
     * Cgf constructor.
     * @param $tableName
     * @param string $prefix
     * @param FrameworkInterface|null $framework
     * @param null $form
     */
    /**
     * Cgf constructor.
     * @param $tableName
     * @param string $prefix 表前缀
     * @param FrameworkInterface|null $framework 框架具体实现类
     * @param Form|null $form  表单组件实现类
     * @param TableInfoInterface $tableInfo 表信息获取类
     * @param $module 要处理的模块
     */
    function __construct($tableName, $prefix='',FrameworkInterface $framework = null, Form $form = null,TableInfoInterface $tableInfo,$module='common')
    {
        $this->tableInfo = $tableInfo;
        $this->tableFullName = $tableName;

        //生成cgf配置文件
        $d = new SqlToCgfDefinition($tableName,$module,$tableInfo);


        //去表前缀
        $tableName = str_replace($prefix,'',$tableName);
        $this->tableName = $tableName;


        //
        $this->definition = new Definition($tableName);
        //var_dump($this->definition->list);


        //设置表单组件实现库
        if(empty($form)) $form = new BootstrapForm();
        $this->form = $form;

        if (!empty($framework)) {
            $this->framework = $framework;
        }

        //各框架验证转接类
        if(empty($validate)) $validate = new ThinkphpValidate();
        $this->validate = $validate;
    }

    function setForm(Form $form){
        $this->form = $form;
    }

    function setValidate(Validate $validate){
        $this->validate = $validate;
    }

    /**
     * 生成select 查询字段
     * @param $tableName
     */
    function generateListSelectColumn()
    {
        $listDefinition = $this->definition->list;
        return array_keys($listDefinition);
    }

    function generateValidate(){
        return $this->validate->generateValidate($this->definition->all);
    }

    function generateJsValidate(){
        return $this->Jsvalidate->generateJsValidate();
    }

    /**
     * 生成thinkphp列表组件标题定义，这个应该由tp的cgf controller自己实现
     */
    function generateListTitle_移入cgfcontroller()
    {
        $show = '';
        //var_dump($this->definition->list);exit;
        foreach ($this->definition->list as $k => $v) {
            //show="id:ID,openid:用户openid,act_goods_id:商品id,act_issue_id:期号,act_goods_name:商品名称,prize_price:奖品市场价,prize_level:奖品等级,prize_goods_id:中奖商品id,prize_goods_name:奖品商品名,prize_type:奖品类型,draw_state:首页显示,prize_state:中奖状态,trans_state:奖品发放,trans_kuaidi_num:物流单号,create_t:创建时间,modify_t:修改时间,auc_count:总竞拍次数"

            //增加关联表字段显示
            if (!empty($v['related_table'])) {
                $definition = new Definition($v['related_table']['table_name']);
                $relationTableDefinition = $definition->list;
                if ($v['related_table']['way'] == 'replace') {
                    foreach ($relationTableDefinition as $k2 => $v2) {
                        $show .= "{$k2}:{$v2['zh']}:sort,";
                    }
                    continue;//显示完关联字段后，自身的字段不显示

                } elseif ($v['related_table']['way'] == 'add') {
                    foreach ($relationTableDefinition as $k2 => $v2) {
                        $show .= "{$k2}:{$v2['zh']}:sort,";
                    }
                }
                //var_dump($relationTableDefinition);

            }

            //将一此枚举类字段转成文本显示
            if (!empty($v['show_text'])) {
                $show .= "{$v['show_text']}:{$v['zh']}:sort,";
                continue;
            }

            $show .= "{$k}:{$v['zh']}:id_link|id^ip:sort,";
        }
        //echo $show;exit;
        return $show;


    }

    /**
     * 生成列表查询字段
     */
    function generateListQueryColumn($isString = false)
    {

        $definition = $this->definition["list"];
        $column = [];
        foreach ($definition as $k => $v) {
            $column[] = $k;
        }

        if (isString) {
            $column = implode(',', $column);
        }

        return $column;

    }

    /**
     * 将表所有字段简化为页面上显示的字段，与后台管理的list里调用相关显示函数有重复，如|optionValue
     * @param $voList
     */
    /*    function generateListShowData( &$voList)
        {
            //var_dump($this->definition);exit;
            $funcList = $this->generateFieldsFunction();

            foreach ($voList as $k => &$v) {
                foreach ($funcList as $fkey => $func) {
                    //var_dump($funcList);exit;
                    //获取函数是否调用标识，如果调用，则执行。
                    //var_dump($func);exit;
                    // if(empty($v['parameter'])) $parameter = $v[$fkey];
                    //$v[$fkey] = call_user_func_array($func['function_name'],[$v,$func['parameter']]);
                    //$v[$fkey] = call_user_func_array($func['function_name'],[$v[$fkey],$func['parameter']]);

                    //$v[$fkey] = call_user_func_array($func['function_name'],[$v[$fkey],$func['parameter'],$v]);
                    //var_dump([$func]);exit;
                    //$parameter = $func['parameter']+$v;
                    $func['parameter'][] = $v;
                    //$v[$fkey] = call_user_func_array($func['function_name'],$func['parameter']);
                    $v[$fkey] = call_user_func_array([self, 'staticGetOptionValue'], $func['parameter']);

                    //var_dump($func['function_name'],[$parameter]);

                }
                var_dump($v);
                exit;
            }
        }*/

    function generateFieldsFunction()
    {
        $funcList = [];
        $definition = $this->definition->list;
        foreach ($definition as $columnName => $v) {
            if (!empty($v['options'])) { //由于实现了status_text功能，取消函数自动生成处理select选项值的数字转成文本
                //$funcList[$columnName] = $this->generateFunctionForOption( $columnName, $v['options']);
                //continue;
            }
            if (!empty($v['function'])) {
                $funcList[$columnName] = $this->generateFunctionForTplFormat($columnName, $v['function']);
                continue;
            }
        }
        return $funcList;
    }


    /**
     * 根据字段option生成函数
     * @param $fieldOption
     */
    function generateFunctionForOption($tableName, $columnName, $options = [])
    {

        //getOptionValue($tableName,$columnName);

        //$functionName = "|$funcName='$name'";
        $func = [];
        $func['function_name'] = "Cgf::staticGetOptionValue";
        $func['parameter'] = [$tableName, $columnName];
        return $func;
    }

    /**
     * 根据定义自定义的函数格式生成函数
     * @param $functionDefinition
     */
    function generateFunctionForTplFormat($columnName, $functionDefinition)
    {

        $functionReg = '/(\w+)\(([^)]*)\)/'; //example: function_name(参数1,参数2)
        preg_match($functionReg, $functionDefinition, $out);
        $functionName = $out[1];
        $strParameter = $out[2];
        $arrParameter = explode(',', $strParameter);
        $arrParameter = array_map(function ($v) {
            return str_replace('"', '', $v);
        }, $arrParameter);


        $func = [];
        $func['function_name'] = $functionName;
        $func['parameter'] = $arrParameter;//[ $columnName];
        return $func;
    }


    function generateAutoCompleteConfig(){}

    /**
     * 根据字符串函数定义转成可调用的函数数组格式，代码同上，只是这里是静态的
     * @param $functionDefinition
     */
    public static function stringFunctionDefinitionToArray( $functionDefinition)
    {

        $functionReg = '/(\w+)\(([^)]*)\)/'; //example: function_name(参数1,参数2)
        preg_match($functionReg, $functionDefinition, $out);
        $functionName = $out[1];
        $strParameter = $out[2];
        $arrParameter = explode(',', $strParameter);
        $arrParameter = array_map(function ($v) {
            return str_replace('"', '', $v);
        }, $arrParameter);


        $func = [];
        $func['function_name'] = $functionName;
        $func['parameter'] = $arrParameter;//[ $columnName];
        return $func;
    }


    /**
     * 根据关联表生成函数
     */
    function generateFunctionForRelateField()
    {
        $arr = explode('=', $commentInfo['rawOption']);
        $tableOption = $arr[1];
        //$functionName = "|tableValue='$tableOption'";
        $function_name = 'tableValue';
        $parameter = $tableOption;
    }


    function getTableDefinition()
    {
        return $this->definition;
    }

    //获取列表关联表和字段
    function getFieldsOfHasRelateTable()
    {
//var_dump($this->definition);
        $relatedFields = [];
        foreach ($this->definition->list as $k => $v) {
            if (!empty($v['related_table'])) {
                $relatedFields[$k] = $v;
            }
        }
        return $relatedFields;

    }

    function getRelatedField($field)
    {
        if (!empty($this->definition->list[$field]['related_table']['related_field'])) {
            return $this->definition->list[$field]['related_table']['related_field'];
        }
        return $field;
    }

    function generateSearch()
    {

        $definition = $this->definition->search;

        $htmlSearch = "";
        foreach ($definition as $k => $v) {
            $htmlInput = $this->form->generate($k, $v);
            //var_dump($htmlInput);
            $htmlSearch .= $this->form->generateSearchInput($htmlInput, $v);
            //var_dump($htmlSearch);exit;


            //$htmlSearch .= str_replace(array_keys($arrAssign),array_values($arrAssign),$htmlTpl);
            //var_dump($html);
            //exit;


            //echo $r;exit;
        }
        //echo $htmlSearch;exit('x2');
        return $htmlSearch;


    }

    function generateAdd($isEdit=false)
    {
        if($isEdit){
            $definition = $this->definition->edit;
        }else{
            $definition = $this->definition->add;
        }

        $definition = $this->definition->edit;
//var_dump($definition);exit;
        $html = "";
        foreach ($definition as $k => $v) {
            $htmlInput = $this->form->generate($k, $v);
            //var_dump($htmlInput);exit;
            $html .= $this->form->generateAddInput($htmlInput, $v);
        }
        return $html;
    }

/*    function generateEdit()
    {
        $definition = $this->definition->edit;

        $html = "";
        foreach ($definition as $k => $v) {
            $htmlInput = $this->form->generate($k, $v);
            $html .= $this->form->generateAddInput($htmlInput, $v);
        }
        return $html;
    }*/

    function generateEdit_old()
    {
        $html = "";
        $generateForm = new generateForm();
        $definition = $this->definition['edit'];
        foreach ($definition as $k => $v) {
            $row = [];
            $row['html'] = $generateForm->generate($k, $v);
            $row['tips'] = "";
            $row['zh'] = "";
            $row['name'] = "";
        }
        echo $html;
        exit;
    }

    function generateForm($tableName)
    {

        $definition = $this->definition;

    }

    function getAllColumnOptions()
    {
        return $this->definition->getAllColumnOptions();
    }

    function staticGetOptionText($tableName, $value)
    {
        $obj = new self($tableName);

    }

    /**
     * 创建列表需要调用函数处理的字段集
     * @param $tableName 表名
     * @return string
     */
    function createFieldOfOwnFunction($tableName)
    {
        $allFields = self::getTableInfoArray($tableName);
        $fields = [];
        foreach ($allFields as $columnInfo) {
            $func = [];
            $commentInfo = $this->parseComment($columnInfo['COLUMN_COMMENT']);

            if (!in_array($this->page, $commentInfo['arrShowPages'][$this->moduleName])
                || (MODULE_NAME == 'User' && $this->page == 'list' && $columnInfo['COLUMN_NAME'] == 'user_id')
            ) { //字段不显示，返回空
                continue;
            }
            //   var_dump($commentInfo);
            $cnName = empty($commentInfo['name']) ? $columnInfo['COLUMN_NAME'] : $commentInfo['name'];
            $name = $columnInfo['COLUMN_NAME'];
            if ($commentInfo['options']) { //有选项，则在列表页调用函数根据key找到对应的value

                if (substr($commentInfo['rawOption'], 0, 6) == 'table=') { //显示关联表字段
                    $arr = explode('=', $commentInfo['rawOption']);
                    $tableOption = $arr[1];
                    //$functionName = "|tableValue='$tableOption'";
                    $function_name = 'tableValue';
                    $parameter = $tableOption;

                } else {
                    if (substr($commentInfo['rawOption'], 0, 10) == 'show_func=') {// 显示函数
                        $arr = explode('=', $commentInfo['rawOption']);
                        //$funcName = $arr[1];
                        $funcDefinition = explode('-', $arr[1]);
                        $funcName = $funcDefinition[0];
                        unset($funcDefinition[0]); //去掉第一个元素，即函数名，后面的都是参数
                        $funcParameters = $funcDefinition;

                        //$functionName = "|$funcName='$name'";
                        $function_name = $funcName;
                        $parameter = $funcParameters;
                    } else { //显示配置选项的name
                        //var_dump($commentInfo['options']);exit;
                        C($name, $commentInfo['options']);
                        //$functionName = "|optionsValue='$name'";//.ucfirst($name);
                        $function_name = 'optionsValue';
                        $parameter = $name;
                    }
                }
                $func = ['function_name' => $function_name, 'parameter' => $parameter];
                $fields[$name] = $func;
            }
        }
        return $fields;
    }


    /**
     * 生成join语句
     */
    function generateJoinSql()
    {

        //2.当前表有关联的表字段时，取关联表信息并合并。实现join功能
        $fieldsOfHasRelateTable = $this->getFieldsOfHasRelateTable();
        //var_dump($fieldsOfHasRelateTable);exit;
        //获取关联字段值,根据关联字段和表，去关联表里找出相应的信息，并合并到现有列表，实现类似数据库join功能
        if (!empty($fieldsOfHasRelateTable))
            foreach ($fieldsOfHasRelateTable as $columnName => $columnDefinition) {
                /* var_dump($relatedTable);
                 echo $k2 ,"\n\n\n\n";*/
                $arrRelatedFieldValues = array_column($voList, $columnName);
                if (empty($arrRelatedFieldValues)) continue;
                $relateTable = $columnDefinition['related_table']['table_name'];

                //各框架要修改实现的地方
                $m = M($relateTable);

                $relatedField = $this->cgf->getRelatedField($columnName);
                $relateTableShowColumn = $columnDefinition['related_table']['fields'];
                array_push($relateTableShowColumn, $relatedField);

                $rRelation = $m->field($relateTableShowColumn)->where([$relatedField => ["in", $arrRelatedFieldValues]])->select();
                //echo $m->getLastSql();exit;
                //var_dump($voList,$rRelation);
                $voList = array_join($voList, $rRelation, [$columnName => $relatedField]);
            }

    }


    function setFramework(FrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * 取出关联表数据并合并
     * @param $voList
     */
    function mergeRelatedTableData(&$voList)
    {

        //2.当前表有关联的表字段时，取关联表信息并合并。实现join功能
        $fieldsOfHasRelateTable = $this->getFieldsOfHasRelateTable();
        //var_dump($fieldsOfHasRelateTable);exit;
        //获取关联字段值,根据关联字段和表，去关联表里找出相应的信息，并合并到现有列表，实现类似数据库join功能
        if (empty($fieldsOfHasRelateTable)) return;

        foreach ($fieldsOfHasRelateTable as $columnName => $columnDefinition) {
            /* var_dump($relatedTable);
             echo $k2 ,"\n\n\n\n";*/
            $arrRelatedFieldValues = array_column($voList, $columnName);
            if (empty($arrRelatedFieldValues)) continue;
            $relateTable = $columnDefinition['related_table']['table_name'];
            $relatedField = $this->getRelatedField($columnName);
            $relateTableShowColumn = $columnDefinition['related_table']['fields'];
            array_push($relateTableShowColumn, $relatedField);
            $where = [$relatedField => ["in", $arrRelatedFieldValues]];

            $rRelation = $this->framework->getRelatedTableData($relateTable, $relateTableShowColumn, $where);

            //$rRelation = $m->field($relateTableShowColumn)->where([$relatedField=>["in",$arrRelatedFieldValues]])->select();
            //echo $m->getLastSql();exit;
            //var_dump($voList,$rRelation);
            $voList = array_join($voList, $rRelation, [$columnName => $relatedField]);
        }
    }

    public static function getOptions($options){

        $firstKey = key($options);

        //如果是函数则调用函数，将返回结果赋给options
        if($firstKey==='function'){
            return self::executeOptionFunction(current($options));
        }else{
            //非函数，直接返回
            return $options;
        }
    }

    //执行option定义的函数
    public static function executeOptionFunction($optionConfValue){


            $optionFunctionDefinition = Cgf::stringFunctionDefinitionToArray( $optionConfValue);
            $parameter = $optionFunctionDefinition['parameter'];
            //将字段值占位符替换为字段实际的值
            $index = array_search('###',$parameter);
            if($index !== false){
                //$parameter[$index] = $v[$column];
            }else{
                //array_unshift($parameter,$v[$column]);//无占位符，将字段值设为第一个参数
                //$parameter[] = $v; //整行记录放入最后1个参数
            }

            $index = array_search('@@@',$parameter);
            if($index !== false){
                //$parameter[$index] = $v[$column];
            }else{
                /*array_unshift($parameter,$v[$column]);//无占位符，将字段值设为第一个参数
                $parameter = [$v[$column],$parameter];
                $parameter[] = $v; //整行记录放入最后1个参数*/
            }

            $functionReturn = call_user_func_array($optionFunctionDefinition['function_name'],$parameter);
            //var_dump($functionDefinition['function_name'],$parameter,$v[$column]);exit;
            //return  call_user_function(); return }

            return $functionReturn;
    }

    /**
     * 执行字段对应的回调函数
     */
    function executeColumnCallback(&$voList){

        $functionList = $this->generateFieldsFunction();

        //4.处理showText，一般用于枚举类数字转为中文显示
        $options = $this->getAllColumnOptions();
        $allListShowText = $this->definition->getAllListShowText();
        /*$allOptions = [];
        foreach ($allListShowText as $column=>$showText){
            $allOptions[$column]=self::getOptions($this->definition->list[$column]['options']);
        }*/
//var_dump($options);
        foreach($voList as $k => &$v){
            //等价 $v['trans_state_text'] =$allOptions['trans_state'][$v['trans_state']];
            foreach ($allListShowText as $column=>$showText){
                //如果是函数，则执行函数
                $v[$showText] =$options[$column][$v[$column]];
            }
            //执行函数
            foreach ($functionList as $column=>$functionDefinition){

                $parameter = $functionDefinition['parameter'];
                //将字段值占位符替换为字段实际的值
                $index = array_search('###',$parameter);
                if($index !== false){
                    $parameter[$index] = $v[$column];
                }else{
                    array_unshift($parameter,$v[$column]);//无占位符，将字段值设为第一个参数
                    //$parameter = [$v[$column],$parameter];
                    $parameter[] = $v; //整行记录放入最后1个参数
                }

                $index = array_search('@@@',$parameter);
                if($index !== false){
                    $parameter[$index] = $v[$column];
                }else{
                    /*array_unshift($parameter,$v[$column]);//无占位符，将字段值设为第一个参数
                    $parameter = [$v[$column],$parameter];
                    $parameter[] = $v; //整行记录放入最后1个参数*/
                }

                $v[$column] = call_user_func_array($functionDefinition['function_name'],$parameter);
                //var_dump($functionDefinition['function_name'],$parameter,$v[$column]);exit;
            }
        }
    }

    /**
     * @param $page
     * @return array
     */
    function getNameAndZh($page='list'):array {
        $pageDefinition = $this->definition->getPageDefinition($page);
        $arr=[];
        foreach ($pageDefinition as $k => $v) {
            $arr[$k] = $v['zh'];
        }
        return $arr;
    }



}

