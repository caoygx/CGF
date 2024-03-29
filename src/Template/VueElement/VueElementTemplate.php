<?php

namespace Cgf\Template\VueElement;

use Cgf\Definition;
use Cgf\Framework\Thinkphp\Bootstrap;
use Cgf\Cgf;
use Cgf\Template;
use Cgf\Template\VueElement\VueElementForm;
use think\Exception;

class VueElementTemplate extends Template
{

    protected $savePath = "./data";
    public $tplDir = __DIR__ . "/tpl";


    /** @var  Definition */
    public $definition;

    /** @var \Cgf\Framework\Thinkphp\Bootstrap|Layui  */
    public $objForm;
    public $generateFile = true;
    public $forceWrite = true;
    public $dirForm;
    public $dirApplication;

    function __construct($definition, $form)
    {
        $this->definition     = $definition;
        $this->dirForm        = ucfirst($form);
//        $this->dirForm        = "tpl";
        $this->dirApplication = Cgf::$config['templateSavePath'];
        if (!file_exists($this->dirApplication)) mkdir($this->dirApplication, 0777, true);

        //设置表单组件实现库
        if (strtolower($form) == 'bootstrap') {
            $this->objForm = new Bootstrap();
        } elseif (strtolower($form) == 'layui') {
            $this->objForm = new Layui();
        }elseif (strtolower($form) == 'vueelement') {
            $this->objForm = new VueElementForm();
        }

    }

    function setObjForm(Form $objForm)
    {
        $this->objForm = $objForm;
    }

//================================= 列表 start ===========================================


    /**
     * 根据cgf生成的信息，调用tp自带的列表组件来显示列表
     * @param $tableName
     * @return string
     */
    function generateListsTemplate()
    {
        $arrTplParameter = [];

        $htmlTableLable       = "";
        $definition = $this->definition->list;
        foreach ($definition as $k => $v) {
            $htmlInput = $this->objForm->generate($k, $v);
            //var_dump($htmlInput);exit;
            $htmlTableLable .= $this->objForm->generateListLabel($htmlInput, $v);
        }

        $arrTplParameter['f_list'] = $htmlTableLable;
        //$this->f_list = $fields;


        //列表页按钮操作
        $arrTplParameter['f_export']         = 0; //有导出按钮 //Default value is necessary, otherwise the template will report an error
        $arrTplParameter['f_add']            = 0; //有添加按钮
        $arrTplParameter['f_batchForbidden'] = 0; //批量禁用按钮
        $arrTplParameter['f_batchDelete']    = 0; //批量删除按钮
        $arrTplParameter['f_showMenu']       = 0; //在菜单中显示


        //var_dump($this->definition->getTableDefinition()['pageButton']);exit('x');
        if (!empty($this->definition->getTableDefinition()['pageButton'])) {
            $pageButton = $this->definition->getTableDefinition()['pageButton'];
            if (array_search('export', $pageButton) !== false) $arrTplParameter['f_export'] = 1;
            if (array_search('add', $pageButton) !== false) $arrTplParameter['f_add'] = 1;
            if (array_search('batchForbidden', $pageButton) !== false) $arrTplParameter['f_batchForbidden'] = 1;
            if (array_search('batchDelete', $pageButton) !== false) $arrTplParameter['f_batchDelete'] = 1;
        }

        $arrTplParameter['f_action'] = 'edit:编辑:id,foreverdel:永久删除:id';
        //action操作生成
        if (!empty($this->definition->getTableDefinition()['action'])) {
            $f_action                    = $this->definition->getTableDefinition()['action'];
            $arrTplParameter['f_action'] = $f_action;
//            var_dump($f_action);exit('x');
        }
        /*else {
            $fieldsKey              = 'tpl_fields.' . strtolower(Cgf::$config['controllerName']);
            $tpl_fields['f_action'] = 'edit:编辑:id,foreverdel:永久删除:id';
            if (!empty($tpl_fields['f_action'])) {
                $f_action = $tpl_fields['f_action'];
            }
        }*/


        //搜索框生成
        //$this->page = 'search';
        $htmlSearch = $this->generateSearch();
//        var_dump($htmlSearch);exit('x');

        //$this->control = CONTROLLER_NAME;//
        //$this->control = '__CONTROLLER__';// 生成模板时用这个
        $arrTplParameter['control']     = strtolower(Cgf::$config['controllerName']);
        $arrTplParameter['html_search'] = $htmlSearch;
        //$this->htmlSearch = $htmlSearch;
        //$this->js_name=$this->tableName;
        $htmlValidateRules = $this->generateAllInputValidateRules($isEdit);
        $htmlJsCloumns = $this->generateAllJsCloumns($isEdit);
        $htmlJsOptions = $this->generateAllJsOptions($isEdit);
        $arrTplParameter['html_validate_rules'] = $htmlValidateRules;
        $arrTplParameter['html_js_cloumns'] = $htmlJsCloumns;
        $arrTplParameter['html_js_options'] = $htmlJsOptions;
        $arrTplParameter['js_name'] = Cgf::$config['controllerName'];
//        var_dump($arrTplParameter);

        //$file = Cgf::$config['parentTemplatePath'] . "/tpl_list.html";

        $file        = $this->tplDir . "/tpl_list.html";
//        $file = "/web/vue-element-admin/src/views/company/index.vue";
//        var_dump($this->tplDir . "/tpl_list.html");exit('x1');
        if (!file_exists($file)) {
            throw new Exception(" $file no exist");
        }
        $content = file_get_contents($file);
        foreach ($arrTplParameter as $k => $v) {
            $content = str_replace('{$' . $k . '}', $v, $content);
        }

//        $content = str_replace('$f_list', $arrTplParameter['f_list'], $content);
//        $content = str_replace('$f_action', $arrTplParameter['f_action'], $content);

        //var_dump($definition);exit;
        //$definition = $this->definition->edit;

        //var_dump($htmlTableLable);exit('x');

        if ($this->generateFile) {
            //生成模板文件到data目录下
            $dir = "./tpl/" . CONTROLLER_NAME;
            $dir = "/web/vue-element-admin/src/views/".strtolower($arrTplParameter['control'])."/" ;
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            $filename = $dir . "/index.vue";

            $this->writeTemplate($filename, $content);
//            if(!file_exists($filename))
//                file_put_contents($filename, $content);

        } else {

            //直接在项目下创建模板
            $dir      = $this->dirApplication;
            $filename = $dir . "/index.html";
            if ($this->definition->isLockDefinition(Cgf::$config['tableName'])) $this->forceWrite = false;
            if ($this->forceWrite || !file_exists($filename)) {
                file_put_contents($filename, $htmlTableLable);
            }
            return $content;


        }


    }

//================================= 列表 end =============================================


//================================= 搜索 start ===========================================
    function generateSearch()
    {

        $definition = $this->definition->search;
        $htmlSearch = "";
        foreach ($definition as $k => $v) {
            $htmlInput = $this->objForm->generate($k, $v);
            $htmlSearch .= $this->objForm->generateSearchInput($htmlInput, $v);
            //var_dump($htmlSearch);exit;

            //$htmlSearch .= str_replace(array_keys($arrAssign),array_values($arrAssign),$htmlTpl);
            //var_dump($html);
            //exit;

            //echo $r;exit;
        }
//        var_dump($htmlSearch);exit('x');
        //echo $htmlSearch;exit('x2');
        return $htmlSearch;


    }

//================================= 搜索 end =============================================

//================================= 添加 start ===========================================
    function generateAddTemplate($isEdit = false)
    {
        $htmlAdd = $this->generateAllInputForAdd($isEdit);
        $htmlValidateRules = $this->generateAllInputValidateRules($isEdit);
        $htmlJsCloumns = $this->generateAllJsCloumns($isEdit);
        $htmlJsOptions = $this->generateAllJsOptions($isEdit);

        //$this->control = CONTROLLER_NAME;//
        //$this->control = '__CONTROLLER__';// 生成模板时用这个
//        $arrTplParameter['control']  = strtolower(CONTROLLER_NAME);
        $arrTplParameter['control']     = strtolower(Cgf::$config['controllerName']);
        $arrTplParameter['html_add'] = $htmlAdd;
        $arrTplParameter['html_validate_rules'] = $htmlValidateRules;
        $arrTplParameter['html_js_cloumns'] = $htmlJsCloumns;
        $arrTplParameter['html_js_options'] = $htmlJsOptions;
        //$this->htmlSearch = $htmlSearch;
        //$this->js_name=$this->tableName;
        $arrTplParameter['js_name'] = $this->tableName;
//        var_dump($arrTplParameter['control']);

        $templateDir = __DIR__;
        $file        = $templateDir . "/tpl/tpl_add.html";

        if (!file_exists($file)) {
            throw new Exception("no exist tpl_add");
        }
        $content = file_get_contents($file);

//        var_dump($arrTplParameter);exit('x');
        foreach ($arrTplParameter as $k => $v) {
            $content = str_replace('{$' . $k . '}', $v, $content);
        }
        //var_dump($content);exit;

        if ($this->generateFile) {
            //生成模板文件到data目录下
//            $dir = "./tpl/" . CONTROLLER_NAME;
            $dir = "/web/vue-element-admin/src/views/".strtolower($arrTplParameter['control'])."/components";
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            $filename = $dir . "/Detail.vue";
            $this->writeTemplate($filename, $content);

        } else {

            //直接在项目下创建模板
            $dir = $this->dirApplication;

            $filename = $dir . "/add.html";
            if ($this->definition->isLockDefinition(Cgf::$config['tableName'])) $this->forceWrite = false;
            if ($this->forceWrite || !file_exists($filename)) {
                file_put_contents($filename, $content);
            }
            return $content;

        }
    }



    function generateAllInputForAdd($isEdit = false)
    {
        if ($isEdit) {
            $definition = $this->definition->edit;
        } else {
            $definition = $this->definition->add;
        }
        //var_dump($definition);exit;
        //$definition = $this->definition->edit;
        $html       = "";
        foreach ($definition as $k => $v) {
            $htmlInput = $this->objForm->generate($k, $v);

            //var_dump($htmlInput);exit;
            $html .= $this->objForm->generateAddInput($htmlInput, $v);
        }
        return $html;
    }

    function generateAllInputValidateRules($isEdit=false){
        if ($isEdit) {
            $definition = $this->definition->edit;
        } else {
            $definition = $this->definition->add;
        }
        //var_dump($definition);exit;
        //$definition = $this->definition->edit;
        $allColumnRules       = [];
        foreach ($definition as $k => $v) {
            $allColumnRules[$k] = $this->objForm->generateValidteRule( $v);
        }

        return json_encode($allColumnRules,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 生成js中的所有字段字典
     * @param false $isEdit
     * @return array|string
     */
    function generateAllJsCloumns($isEdit=false){
        if ($isEdit) {
            $definition = $this->definition->edit;
        } else {
            $definition = $this->definition->add;
        }
        //var_dump($definition);exit;
        //$definition = $this->definition->edit;
        $allColumns      = '';
        $i=0;
        foreach ($definition as $k => $v) {
            $comma=',';
            if($i==count($definition)-1){
                $comma='';
            }
            $allColumns .= $k. ":''{$comma} //{$v['zh']}\n";
            $i++;
        }
        return $allColumns;
    }

    /**
     * 生成所有字段的options的js代码
     * @param false $isEdit
     * @return array|string
     */
    function generateAllJsOptions($isEdit=false){
        if ($isEdit) {
            $definition = $this->definition->edit;
        } else {
            $definition = $this->definition->add;
        }
        //var_dump($definition);exit;
        //$definition = $this->definition->edit;
        $allColumns      = '';
        $i=0;

        $allOptions=[];
        foreach ($definition as $k => $v) {
            if(!empty($v['options'])){
                $option = $this->generateColumnOption($v['options']);
                $allOptions[]=$k."_options:".json_encode($option,JSON_UNESCAPED_UNICODE);
            }
        }
        $ret = implode(',',$allOptions);
        if(!empty($ret)){
            return $ret.',';
        }
    }

    /*
        array(3) {
          [0]=>
          string(6) "正常"
          [1]=>
          string(6) "禁用"
          [2]=>
          string(6) "启用"
        }
     * */
    function generateColumnOption($arrOptionDefinition){
        $vueOptions=[];
        foreach ($arrOptionDefinition as $k=>$v){
            $vueOption['value']=$k;
            $vueOption['key']=$k;
            $vueOption['label']=$v;
            $vueOptions[] = $vueOption;
        }
        return $vueOptions;
    }



//================================= 添加 end ===========================================


    /**
     * 创建列表select 查询字段
     * @param $tableName 表名
     * @return string
     */
    function createListSelectFields($tableName)
    {
        $allFields = self::getTableInfoArray($tableName);
        $fields    = [];
        //var_dump($allFields);
        foreach ($allFields as $columnInfo) {


            $func        = [];
            $commentInfo = $this->parseComment($columnInfo['COLUMN_COMMENT']);
            /*var_dump($columnInfo['COLUMN_NAME']);
            var_dump($commentInfo);echo "\n\n";
            var_dump($this->page);
            var_dump($commentInfo['arrShowPages']);*/

            if (in_array($this->page, $commentInfo['arrShowPages'][$this->moduleName])) {
                $fields[] = $columnInfo['COLUMN_NAME'];
            }

            $showAttribute = $commentInfo['arrShowPages'][$this->moduleName];
            if (!in_array($this->page, $commentInfo['arrShowPages'][$this->moduleName])
                || (MODULE_NAME == 'User' && $this->page == 'list' && $columnInfo['COLUMN_NAME'] == 'user_id')
            ) { //字段不显示，返回空
                continue;
            }

            $cnName = empty($commentInfo['name']) ? $columnInfo['COLUMN_NAME'] : $commentInfo['name'];
            $name   = $columnInfo['COLUMN_NAME'];

        }
        //var_dump($fields);
        //exit;
        return $fields;
    }


    function edit()
    {

        //自动获取添加模板
        layout(false);
        $tpl = $this->generateAddTpl(true);
        layout(true);

        $id          = I($this->m->getPk());
        $where       = [];
        $where['id'] = $id;
        if (MODULE_NAME == 'User') $where['user_id'] = $this->user_id;
        $vo = $this->m->where($where)->find();
        if (method_exists($this, '_replacePublic')) {
            $this->_replacePublic($vo);
        }
        $this->vo = $vo;
        $this->assign('action', 'edit');

        $this->pageTitle = $this->getControllerTitle(CONTROLLER_NAME) . "编辑";
        $this->cgf       = "cgf";

        $this->toview("", "add");
    }


    //保存添加和编辑
    function save()
    {
        if (haveUploadFile()) {
            $uploadInfo = $this->commonUpload();
            if (!empty($uploadInfo)) {
                foreach ($uploadInfo as $k => $v) {
                    $_POST[$k] = $v['path'];
                }
            }
        }
        //var_dump($this->isAjax());exit;
        //$id = I($this->m->getPk ());
        $id = I('id');
        //$vo = $this->m->getById ( $id );


        //自动验证
        //$tableInfo = new TableInfo('',$this->dbConnection);
        $validate = new ThinkphpValidate();
        $this->setValidate($validate);
        $tableName = $this->m->getTableName();
        $rules     = $this->generateValidate($tableName);
        //$auto = $tableInfo->getAutoComplete($tableName);
        $data = $this->m->create();

        if (empty($id)) {
            $isNew = 1;
            unset($_POST['id']);
            //$r  = $this->m->validate($rules)->create ();
            /*var_dump($_GET);
            var_dump($_POST);
            var_dump($_REQUEST);
            var_dump($r);eixt;*/
            //$_POST['user_id'] = $this->user_id; //添加时默认加上用户id
            if (false === $this->m->validate($rules)->create()) {
                //$this->error ( $this->m->getError () );
            }
            $r  = $this->m->add();
            $id = $r;

        } else {
            if (false === $this->m->validate($rules)->create()) {
                $this->error($this->m->getError());
            }
            $r = $this->m->save();
        }

        //保存当前数据对象
        if (method_exists($this, '_after_save') && $id) $this->_after_save($id);

        //echo $this->m->getLastSql();exit;
        if ($id !== false) { //保存成功
            $ret = [];
            //$this->assign ( 'jumpUrl', cookie( '_currentUrl_' ) );

            if (I('multiple')) { //多图，需要关联图片到对应的主题,
                //注意，不支持一个主题里有两个以上字段都是多图上传。此情况需要在图片表里增加标识，属于哪个字段，
                //或在主题字段里存储用逗号分隔的图片id,如:1,2,3

                //其实不用forech,直接取$uploadInfo[0]即可，反正又不支持多字段多图上传
                if (!empty($uploadInfo)) {
                    foreach ($uploadInfo as $k => $v) {
                        $ids[$k] = array_column($v, 'id');
                        M('File')->where(['id' => ['in', $ids[$k]]])->setField('tid', $id);

                    }

                    $ret = $uploadInfo;
                }

            }


            if ($this->successRedirectUrl) {
                $redirectUrl = $this->successRedirectUrl . $id;
            } elseif ($isNew && $this->newInfoNextUrl) {
                $redirectUrl = $this->newInfoNextUrl . $id;
            } else {
                //$redirectUrl = cookie( '_currentUrl_' );
                $redirectUrl = "/" . CONTROLLER_NAME;
            }
            C('info_id', $id);
            $ret['id'] = $id;
            $this->success($ret, '成功', $redirectUrl);
        } else {
            //失败提示
            $this->error('失败了!');
        }


    }

    /**
     * @param $tableName 表名
     * @param string $page 生成什么页面
     * @return string|void
     */
    function generateForm($tableName, $isCreateFile = false)
    {
        empty($tableName) && $tableName = I('tableName');
        $columnNameKey  = strtoupper($this->getColumnNameKey());
        $str            = '';
        $selectedFields = I('tableFields');
        if (empty($tableName)) {
            $this->generateAll();
            return;
        } else {
            $allFields = self::getTableInfoArray($tableName);
        }
        $str .= '';

        foreach ($allFields as $columnInfo) {
            if (!empty($selectedFields) && !in_array($columnInfo['COLUMN_NAME'], $selectedFields)) continue;
            //if (!I('hasId') && $columnInfo['COLUMN_KEY'] == "PRI") continue;
            $str .= $this->createFormRow($columnInfo);
            //$str .= '<option value="'.$columnInfo[$columnNameKey].'" >'.$columnInfo[$columnNameKey]."</option>\r\n";
        }

        if ($this->haveUpload) {
            $uploadHeader = 'enctype="multipart/form-data"';
        }
        $formHeader = '<form class="form-horizontal" role="form"  method="post" action="__URL__/save/" ' . $uploadHeader . '>';
        $str        = $formHeader . $str;

        $this->allRows = $str;
        $r             = $this->fetch("tpl_form");
//var_dump($r);exit;
        //var_dump($this->arrOptions);exit('x1');
        foreach ($this->arrOptions as $k => $v) {
            $this->assign('opt_' . $k, $v);
        }

        $tpl_content = $r;
        if ($isCreateFile) {
            return $tpl_content;
        }
        $r = $this->fetch("", $r);
        return $r;
        echo $r;

    }


    /**
     * 将表所有字段简化为页面上显示的字段，与后台管理的list里调用相关显示函数有重复，如|optionValue
     * @param $voList
     */
    function dateToViewModel(&$voList)
    {
        $tableInfo = new TableInfo('list', $this->dbConnection);
        $tableName = $this->m->getTableName();
        $funclist  = $tableInfo->createFieldOfOwnFunction($tableName);
        foreach ($voList as $k => &$v) {
            foreach ($funclist as $fkey => $func) {
                //获取函数是否调用标识，如果调用，则执行。
                //var_dump($func);exit;
                // if(empty($v['parameter'])) $parameter = $v[$fkey];
                //$v[$fkey] = call_user_func_array($func['function_name'],[$v,$func['parameter']]);
                //$v[$fkey] = call_user_func_array($func['function_name'],[$v[$fkey],$func['parameter']]);
                $v[$fkey] = call_user_func_array($func['function_name'], [$v[$fkey], $func['parameter'], $v]);

                //var_dump($func['function_name'],[$parameter]);

            }
            //var_dump($v);
            //exit;
        }
    }

    /**
     * 获取关联表数组
     * @param string $tableName 关联表名
     * @param array $field 关联表要显示的字段 ['id',"name"]
     * @param array $where 查询条件['关联字段'=>关联的值], in
     * @return array 返回关联表数据
     */
    public function getRelatedTableData(string $tableName, array $field, array $where): array
    {
        $m = M($tableName);
        //$relatedField = $this->getRelatedField($columnName);
        //[$relatedField=>["in",$arrRelatedFieldValues]]
        $rRelation = $m->field($field)->where($where)->select();
        return $rRelation;
    }

//    function realExportExcel($list)
//    {
//        $xlsCell = $this->getNameAndZh();
//        $xlsName = $this->definition->getTableDefinition()['title'];
//
//        header('Content-type: text/html; charset=utf-8');
//        vendor('PHPExcel.Classes.PHPExcel');
//
//        //var_dump($xlsData);exit;
//        foreach ($list as $k => $v) {
//            //$xlsData[$k]['status'] = 1 ? '正常':'锁定';
//            //$xlsData[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
//        }
//        exportExcel($xlsName, $xlsCell, $list);
//    }
//
//    function exportExcel()
//    {
//        $this->index();
//    }
//
//    function _before_exportExcel()
//    {
//        C('URL_MODEL', 0); //解决时间搜索中 空格被转成+号，导致下一页内容无法显示
//        if (empty($_REQUEST['listRows'])) $_REQUEST['listRows'] = 50;
//    }


    /**
     * 生成thinkphp列表组件标题定义，这个应该由tp的cgf controller自己实现
     */
    function generateListTitle()
    {
        $show = '';
        foreach ($this->definition->list as $k => $v) {
            //show="id:ID,openid:用户openid,act_goods_id:商品id,act_issue_id:期号,act_goods_name:商品名称,prize_price:奖品市场价,prize_level:奖品等级,prize_goods_id:中奖商品id,prize_goods_name:奖品商品名,prize_type:奖品类型,draw_state:首页显示,prize_state:中奖状态,trans_state:奖品发放,trans_kuaidi_num:物流单号,create_t:创建时间,modify_t:修改时间,auc_count:总竞拍次数"

            //增加关联表字段显示
            if (!empty($v['related_table'])) {
                $definition              = new Definition($v['related_table']['table_name']);
                $relationTableDefinition = $definition->list;
                if ($v['related_table']['way'] == 'replace') {
                    //foreach ($relationTableDefinition as $k2 => $v2) {
                    foreach ($v['related_table']['fields'] as $k2 => $v2) {
                        $show .= "{$v2}:{$relationTableDefinition[$v2]['zh']},";
                    }
                    continue;//显示完关联字段后，自身的字段不显示

                } elseif ($v['related_table']['way'] == 'add') {
                    //foreach ($relationTableDefinition as $k2 => $v2) {
                    foreach ($v['related_table']['fields'] as $k2 => $v2) {
                        $show .= "{$v2}:{$relationTableDefinition[$v2]['zh']},";
                    }
                }
                //var_dump($relationTableDefinition);

            }

            //将一此枚举类字段转成文本显示
            if (!empty($v['show_text'])) {
                $show .= "{$v['show_text']}:{$v['zh']},";
                continue;
            }

            $js_function = "";
            if (!empty($v['js_function'])) {
                $functionName = $v['js_function']['function_name'];
                $js_function  = "{$this->tableName}_$functionName";
                if (!empty($v['js_function']['parameter'])) {
                    $parameter   = $v['js_function']['parameter'];
                    $parameter   = implode('^', $parameter);
                    $js_function .= "|{$parameter}";
                }
            }

            //|img
            $tpl_function = '';
            if (!empty($v['tpl_function'])) {
                $reg = '/(.+?)\((.*)\)/';
                preg_match($reg, $v['tpl_function'], $out);
                $tpl_function           = $out[1];
                $tpl_function_parameter = $out[2];
                //$tpl_function="|{$v['tpl_function']}={$out[1]}";
                $tpl_function = "|{$tpl_function}";
                if (!empty($tpl_function_parameter)) $tpl_function .= "=$tpl_function_parameter";
            }

            $show .= "{$k}$tpl_function:{$v['zh']}:" . $js_function . ":sort,";
        }
        $show = implode(',', array_filter(explode(',', $show)));
        //echo $show;exit;
        return $show;


    }

















    //==================================生成 controller,view,mode==========================

    /**
     * 生成controller
     * @param $className
     */
    function generateController($className)
    {
        $tplPath   = T('tpl_controller');
        $tpl       = file_get_contents($tplPath);
        $tpl       = str_replace('{$className}', $className, $tpl);
        $className = parse_name($className, 1);
        $path      = $this->savePath . "/Controller";
        if (!file_exists($path)) mkdir($path, 0777, true);
        file_put_contents("{$path}/{$className}Controller.class.php", $tpl);
    }

    /**
     * 生成model
     * @param $className
     */
    function generateModel($className)
    {
        $tplPath   = T('tpl_model');
        $tpl       = file_get_contents($tplPath);
        $tpl       = str_replace('{$className}', $className, $tpl);
        $className = parse_name($className, 1);
        $path      = $this->savePath . "/Model";
        if (!file_exists($path)) mkdir("$path", 0777, true);
        file_put_contents("{$path}/{$className}Model.class.php", $tpl);
    }

    /**
     * 生成view里的模板文件,add.html,list.html 添加表单，和列表
     * @param $tableName
     */
    function generateView($tableName)
    {

        $tableInfoArray = getTableInfoArray($tableName);
        $columnNameKey  = strtoupper(getColumnNameKey());
        $str            = '';

        //生成添加表单
        $str .= '<form class="form-horizontal" role="form"  method="post" action="__URL__/save/">';
        foreach ($tableInfoArray as $columnInfo) {
            //var_dump($columnInfo);exit;
            $str .= $this->createFormRow($columnInfo);
            //$str .= '<option value="'.$columnInfo[$columnNameKey].'" >'.$columnInfo[$columnNameKey]."</option>\r\n";
        }

        $this->allRows = $str;
        $str           = $this->fetch("tpl_form");

        $prefix    = C("DB_PREFIX");
        $className = ucfirst(str_replace($prefix, '', $tableName));
        $className = parse_name($className, 1);
        $path      = $this->savePath . "/View/$className/";
        if (!file_exists($path)) mkdir("$path", 0777, true);
        file_put_contents("$path/add.html", $str);


        //生成列表模板
        //$tplPath = T('tpl_list');
        //$tpl = file_get_contents($tplPath);
        $fields        = $this->createListFields($tableInfoArray);
        $this->fields  = $fields;
        $this->control = '__CONTROLLER__';
        $str           = $this->fetch("tpl_list");
        file_put_contents("$path/index.html", $str);
    }
}
