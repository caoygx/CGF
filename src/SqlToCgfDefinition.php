<?php

namespace Cgf;
/**
 * 表cgf定义转成数组配置
 * Class SqlToCgfDefinition
 * @package Cgf
 */
class SqlToCgfDefinition
{
    public $definitionDir = "definition";
    public $originalDefinition = [];//源定义配置
    public $compiledDefinition = [];//编译后的配置
    public $modules = ['common', 'admin','user','home'];//,'user','home','api'
    public $currentModule = 'common';
    public $tableName = "";

    /**
     * 是否强制写配置文件，true表示强制覆盖配置文件，但优先级小于表注释里的lock字段。
     * @var bool
     */
    public $forcedWriteFile = true;

    /**
     * 所有字段属性定义
     * 格式：['id'=>['name'=>'id',zh=>'编号','arrShowPages'=>['admin'=>['add','list']]]]
     * @var array
     */
    public $allColumnAttribute = [];

    /**
     * cgf定义配置文件
     * 格式:'base' => [    'id' => [    'zh' => '编号',    ]]
     * @var array
     */
    public $cgfDefinition = [];


    public $saveDefinitionDir = "";


    public $tablePrefix = "pm_";
    public $allModuleDefinition = [];
    public $enableModule=false;
    public $tableInfo = [];

    public $savePath;

    /**
     * @param string $savePath
     */
    public function setSavePath($savePath)
    {
        $this->savePath = $savePath;
    }


    /**
     * SqlToCgfDefinition constructor.
     * @param $tableName 要处理的表名
     * @param string $module 模块
     * @param TableInfoInterface|null $tableInfoHandle 表信息解析器
     */
    function __construct( $tableInfo,$module = "common",$savePath)
    {
        $this->currentModule = $module;
        $this->tableName = $tableInfo['tableName'];
        $this->tableInfo = $tableInfo;
        $this->saveDefinitionDir = $savePath."";
        if(!file_exists($this->saveDefinitionDir)){
            mkdir($this->saveDefinitionDir,0777,true);
        }


        $this->getTableAllColumnDefinition();

        $this->generateCgfDefinitionFile();
    }

    //获取表所有字段定义
    function getTableAllColumnDefinition(){

        $allColumn = $this->tableInfo['allColumnDefinition'];

        foreach ($allColumn as $k =>$column){
            $this->allColumnAttribute[$column['COLUMN_NAME']] = CommentParser::getColumnAttribute($column);
        }


        //var_dump($r);exit('x');


    }

    /**
     * 获取表注释定义
     */
    function getTableInfo(){
        $comment = $this->tableInfo['tableComment'];
        //表注释定义解析
        $arrComment =  CommentParser::parseTableComment($comment);
        $arrComment['name'] = $this->tableName;
        //var_dump($arrComment);exit('x');
        return $arrComment;

        //$this->cgfDefinition['tableInfo'] =
    }


    /*
            ['rinse_status' =>
                array(
                    'name' => 'rinse_status',
                    'type' => 'select',
                    'size' => 10,
                    'zh' => '数据清洗状态',
                    'tips' => '提示',
                    'showPage' => '1111',
                    'arrShowPages' =>
                        array(
                            'admin' =>
                                array(
                                    0 => 'add',
                                    1 => 'edit',
                                    2 => 'list',
                                    3 => 'search',
                                ),
                            'user' =>
                                array(
                                    0 => 'add',
                                    1 => 'edit',
                                    2 => 'list',
                                    3 => 'search',
                                ),
                            'home' =>
                                array(
                                    0 => 'list',
                                    1 => 'show',
                                ),
                        ),
                    'checkType' => '',
                    'options' =>
                        array(
                            0 => '未处理',
                            1 => '已处理',
                        ),
                    'rawOption' => '0:未处理,1:已处理',
                )
            ];
    */
    /**
     * 展开生成所有模块和页面定义
     */
    function unfoldAllModulePage($moduleName){
        foreach ($this->allColumnAttribute as $k => $columnAttribute) {
            if(!empty($columnAttribute['arrShowPages']) && is_array($columnAttribute['arrShowPages'])){
                foreach ($columnAttribute['arrShowPages'] as $moduleName => $pages){
                    $this->toFullDefinition($moduleName,$pages,$columnAttribute);
                }

            }
        }
    }

    /**
     * 目录格式
     * $cgf=[
     * "base"=>["字段1"=>[],"字段2"=>[]],
     * "list"=>[],
     * "search"=>[],
     * "add"=>[],
     * "edit"=>[],
     * ]
     *
     */
    /**
     * 获取指定模块的cgf定义
     * @param $moduleName
     */
    function getModuleCgfDefinition($moduleName){

        $cgfDefinition = [];
        foreach ($this->allColumnAttribute as $k => $columnAttribute) {


            //var_dump($columnAttribute);
            if(!empty($columnAttribute['arrShowPages']) && is_array($columnAttribute['arrShowPages'])){
                //去除字段页面定义
                $showPages = $columnAttribute['arrShowPages'];
                unset($columnAttribute['arrShowPages']);
            }

            if(!empty($columnAttribute['options'])){ //列表页自动将有options字段转成show_text
                $columnAttribute['show_text'] = $columnAttribute['name']."_text";
            }

            //生成base定义，并将其放入数组开头
            $baseCgfDefinition['base'][$columnAttribute['name']] = $columnAttribute;


            if(!empty($showPages)){
                //生成增改列表搜定义
                foreach ($showPages[$moduleName] as $pages){
                    //所有页面字段都是完整的定义
                    //$cgfDefinition[$pages][$columnAttribute['name']] = $columnAttribute;

                    //只有base是完整定义
                    $cgfDefinition[$pages][$columnAttribute['name']] = [];
                }
            }

        }
        //exit;
        $cgfDefinition = array_merge($baseCgfDefinition,$cgfDefinition);
        return $cgfDefinition;

    }


    /*array(3) {
            ["admin"]=>
              array(4) {
                [0]=>
                string(3) "add"
                [1]=>
                string(4) "edit"
                [2]=>
                string(4) "list"
                [3]=>
                string(6) "search"
              }
              ["user"]=>
              array(4) {
                [0]=>
                string(3) "add"
                [1]=>
                string(4) "edit"
                [2]=>
                string(4) "list"
                [3]=>
                string(6) "search"
              }
              ["home"]=>
              array(2) {
                [0]=>
                string(4) "list"
                [1]=>
                string(4) "show"
              }
            }
    */

    //将上面格式转成
    // $all['admin']['list']=[列1,列2]
    // $all['admin']['add']=[]
    // $all['admin']['edit']=[]

    /**
     *
     * @param $pages
     * @param $attribute
     * @return array
     *
     */
    function toFullDefinition($moduleName,$pages,$attribute){

        unset($attribute['arrShowPages']);;
        foreach ($pages as $page){
            $this->allModuleDefinition[$moduleName][$page][$attribute['name']] = $attribute;
        }
    }

    /**
     * generate definition file
     * @param string $moduleName
     */
    function generateCgfDefinitionFile($moduleName="admin"){
        if($this->enableModule){
            foreach ($this->modules as $v){
                $this->cgfDefinition[$v] = $this->getModuleCgfDefinition($moduleName);
            }

        }else{
            $this->cgfDefinition = $this->getModuleCgfDefinition($moduleName);
        }

        //表自身注释信息
        $this->cgfDefinition['tableInfo'] = $this->getTableInfo();
        //var_dump($this->cgfDefinition);exit;
        /*$this->generateBase();
        $this->generateList();
        $this->generateSearch();
        $this->generateAdd();
        $this->generateEdit();*/

        //$this->unfoldAllModulePage();

        //$this->generateBase();
        //$arrStr = var_export($this->allModuleDefinition,1);

        $filePath = $this->saveDefinitionDir."/".str_replace($this->tablePrefix,'',$this->tableName).".php";

        if(file_exists($filePath)){
            $lockDefinitionFile = $this->tableInfo['isLockDefinition'];
            if($lockDefinitionFile){
                return false;
            }
            if(!$this->forcedWriteFile){
                return false;
            }
        }
        $arrStr = var_export($this->cgfDefinition,1);
        $content = "<?php \n return ".$arrStr.";";
        file_put_contents($filePath,$content);
    }

    //生成基本字段
    function generateBase(){
        $this->cgfDefinition['base'] = $this->allColumnAttribute;
    }

    //生成列表选项
    function generateList(){

        $this->cgfDefinition['list'] = [];
    }

    //生成搜索选项
    function generateSearch(){
        $this->cgfDefinition['search'] = [];
    }

    //生成添加选项
    function generateAdd(){
        $this->cgfDefinition['add'] = [];
    }

    //生成编辑选项
    function generateEdit(){
        $this->cgfDefinition['edit'] = [];
    }

    //生成函数
    function getHtmlType($parser,$column){
        return $parser->getColumnAttribute($column);
    }

}