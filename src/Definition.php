<?php

namespace Cgf;

/**
 * 将cgf定义配置文件生成完整的配置
 * Class Definition
 * @package Cgf
 */
class Definition
{
    public $savePath;
    public $originalDefinition = [];//源定义配置
    public $compiledDefinition = [];//编译后的配置
    public $availableModule = [];//,'user','home','api'
    public $currentModule = 'common';
    public $tableName;

    function __construct($tableName, $savePath,$module = "common")
    {
        $this->savePath           = $savePath.'';
        $this->tableName          = $tableName;
        $this->originalDefinition = $this->getOriginalDefinition();
        $this->availableModule    = Cgf::$config['availableModule'];
        $this->currentModule      = $module;
        $this->compile();
    }

    function getOriginalDefinition()
    {
        return include($this->savePath . '/' . $this->tableName . '.php');
    }

    function compile()
    {
        //var_dump($this->originalDefinition);exit;
        $base = $this->originalDefinition['base'];
        $d = [];
        //所有定义合并，后面覆盖前面。按base,list,search,add,edit顺序

        $d['all'] = $this->mergeAllPageProperty($base, $this->originalDefinition['list']);
        $d['add'] = $this->mergeProperty($base, $this->originalDefinition['add']);
        $d['edit'] = $this->mergeProperty($base, $this->originalDefinition['edit']);
        $d['list'] = $this->mergeProperty($base, $this->originalDefinition['list']);
        //var_dump($d['list']);exit;
        $d['search'] = $this->mergeProperty($base, $this->originalDefinition['search']);

        //表自身定义信息编译
        $d['tableInfo'] = $this->compileTableInfo($this->originalDefinition['tableInfo']);

        //编译出各模块定义
        foreach ($this->availableModule as $k => $module) {
            $this->compiledDefinition[$module] = $d;
        }
        //var_dump($this->compiledDefinition);exit;

        //编译各模块特殊定义
        foreach ($this->availableModule as $k => $module) {
            if (!empty($this->originalDefinition['module'][$module])) {
                $moduleDefinition = [];
                $moduleDefinition = $this->originalDefinition['module'][$module];
                foreach ($moduleDefinition as $pageKey => $pageDefinition) {
                    if (!empty($pageDefinition)) {
                        $this->compiledDefinition[$module][$pageKey] = array_merge_recursive($base, $pageDefinition);
                    }
                }
            }
        }


    }

    /**
     * 递归合并数组，页面没有特殊定义，则返回base定义
     * $a["zh"]=>"标题1";
     * $b["zh"]=>"替换的标题";
     * mergeProperty($a,$b) 并没有达到想要的["zh"]=>"替换的标题",而是变成合并["zh"=>[标题1,"替换的标题"]]
     * @param $base
     * @param $pageDefinition
     * @return mixed
     */
    function mergeProperty($base, $pageDefinition)
    {
        if(empty($pageDefinition) || !is_array($pageDefinition) ){

            //var_dump($pageDefinition,$base);exit;
            return $base;
        }

        foreach ($pageDefinition as $column => $property) {
            if (isset($base[$column])) {
                $pageDefinition[$column] = array_merge($base[$column], $property); //array_merge_recursive 用递归则无法重写base的定义
            }
        }
        return $pageDefinition;
    }

    /**
     * 合并所有页面定义的属性，变成一个最完整的定义集合
     * @param $base
     * @param $pageDefinition
     * @return mixed
     */
    function mergeAllPageProperty($base, $pageDefinition)
    {
        if(is_array($pageDefinition) && !empty($pageDefinition))
        foreach ($pageDefinition as $column => $property) {
            if(empty($property)) continue;

            if(!is_string($property)){
                $column = $property;
                $property = [];
            }


            $base[$column] = array_merge($base[$column], $property);
        }
        return $base;
    }

    function getAllDefinition()
    {
        return $this->compiledDefinition[$this->currentModule];
    }

    function getPageDefinition($page)
    {
        //var_dump($this->compiledDefinition[$this->currentModule]);exit;
        return $this->compiledDefinition[$this->currentModule][$page];
    }

    function __get($name)
    {
        return $this->getPageDefinition($name);
    }

    /**
     * 列表显示的特殊字段，通常是有options的字段
     * 如status|0:禁用,1: 显示 列表时需要显示，中文文字，但传参数时希望传递数字0,1
     * 为解决此问题，增加了status_text字段，用户中文文字显示
     * @return array
     */
    function getAllListShowText()
    {
        $list = $this->compiledDefinition[$this->currentModule]['list'];
        $showTextColumns = [];
        foreach ($list as $columnName => $columnDefinition) {
            if (!empty($columnDefinition['show_text'])) { //有定义show_text
                $showTextColumns[$columnName] = $columnDefinition['show_text'];
            }
        }
        return $showTextColumns;
    }


    function getOptions($column)
    {
        $firstKey = key($this->compiledDefinition[$this->currentModule]['all'][$column]['options']);
        $firstValue = current($this->compiledDefinition[$this->currentModule]['all'][$column]['options']);

        //如果是函数则调用函数，将返回结果赋给options
        if($firstKey==='function'){ //int与string比较，会强制将string转为0
            $optionFunctionDefinition = Cgf::stringFunctionDefinitionToArray( $firstValue);
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

            $this->compiledDefinition[$this->currentModule]['all'][$column]['options']=$functionReturn;
        }

        //否则直接返回字段定义的options
        return $this->compiledDefinition[$this->currentModule]['all'][$column]['options'];

    }

    function getAllColumnOptions()
    {
        $options = [];
        $list = $this->compiledDefinition[$this->currentModule]['all'];

        foreach ($list as $column => $columnDefinition) {
            if (!empty($columnDefinition['options'])) {
                $options[$column] = $this->getOptions($column);
            }
        }
        //var_dump($options);exit;
        return $options;
    }

    function compileTableInfo($tableInfo){
        $commonOperation = ['add','edit','delete','resume','resume','pass'];
        if(!empty($tableInfo['action'])){
            $action = $tableInfo['action'];
            $arrAction = explode(',',$action);
            //给操作模板函数(通常是js函数)加上表前缀，防冲突
            foreach ($arrAction as $k=>&$v){
                $operateConf = explode(':',$v);
                $operateFunctionName = $operateConf[0];
                if(!in_array($operateFunctionName,$commonOperation)) {
                    $operateFunctionName = $this->tableName . '_' . $operateFunctionName;
                }
                $operateConf[0] = $operateFunctionName;
                $v = implode(':',$operateConf);
            }
            $action = implode(',',$arrAction);

            //$action =  preg_replace('/(.+?):/',"{$this->tableName}_$1:",$action);
            $tableInfo['action'] = $action;
        }

        return $tableInfo;
    }

    function getTableDefinition()
    {
        //var_dump($this->compiledDefinition[$this->currentModule]);exit;
        return $this->compiledDefinition[$this->currentModule]['tableInfo'];
    }


    function isLockDefinition(){
        if(empty($this->compiledDefinition[$this->currentModule]['tableInfo']['property'])){
            return false;
        }else{
            return $this->compiledDefinition[$this->currentModule]['tableInfo']['property'] == 'lock';
        }

    }
}