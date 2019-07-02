<?php
namespace Common;
use Common\Cgf\TableInfoInterface;


/**
 * mysql表信息获取类，各框架需自己实现 TableInfoInterface接口
 * Class MysqlTableInfo
 * @package Common\Cgf
 */
class MysqlTableInfo implements TableInfoInterface
{

    public $options = []; //存储数组序列化后的字符串格式
    public $arrOptions = []; //存储数组格式
    public $data = null; //记录的值
    public $dbConf = [];
    public $model = null;
    public $dbName = "";
    public $tableName;

    /**
     * TableInfo constructor.
     */
    function __construct($dbConf=[],$tableName)
    {
        if(!empty($dbConf)){
            $this->model = M('','',$dbConf);
            $this->dbConf = $dbConf;
            //$this->dbName = $dbConf['DB_NAME'];
        }else{
            $this->model = M();
            $this->dbConf['DB_TYPE'] = C('DB_TYPE');
            $this->dbConf['DB_HOST'] = C('DB_HOST');
            $this->dbConf['DB_NAME'] = C('DB_NAME');
            $this->dbConf['DB_USER'] = C('DB_USER');
            $this->dbConf['DB_PWD'] = C('DB_PWD');
            $this->dbConf['DB_PREFIX'] = C('DB_PREFIX');
            $this->dbConf['DB_CHARSET'] = C('DB_CHARSET');

        }
        $this->tableName = $tableName;
    }

    //获取表名列表
    public function getTableNameList()
    {
        $dbType = $this->dbConf['DB_TYPE'];
        if (in_array($dbType, array('mysql', 'mysqli'))) {
            $dbName = $this->dbConf['DB_NAME'];
            $result = Array();
            $tempArray = $this->model->query("select table_name from information_schema.tables where table_schema='" . $dbName . "' and table_type='base table'");
            foreach ($tempArray as $temp) {
                $result[] = $temp['table_name'];
            }
            return $result;
        } else { //sqlite
            $result = Array();
            $tempArray = $this->model->query("select * from sqlite_master where type='table' order by name");
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

    /**
     * 获取表定义
     * @param $tableName
     * @return array
     */
    public function getTableDefinition($tableName)
    {
        $dbType = $this->dbConf['DB_TYPE'];
        if ($dbType == 'mysql') {
            $dbName = $this->dbConf['DB_NAME'];
            //$dbPrefix = $this->dbConf['DB_PREFIX'];
            $sql = "select * from information_schema.columns where table_schema='" . $dbName . "' and table_name='" . '' .  $dbPrefix .$tableName . "'";
            //echo $sql;exit;
            $result = $this->model->query($sql);
            return $result;
        } else { //sqlite
            $result = $this->model->query("pragma table_info (" . $dbPrefix . $tableName . ")");
            return $result;
        }
        $this->error('数据库类型不支持');
    }


//根据数据库类型获取列名键
    public function getColumnNameKey()
    {
        $dbType = $this->dbConf['DB_TYPE'];
        if ($dbType == 'mysql') {
            return MYSQL_COLUMN_NAME_KEY;
        } else {
            return SQLITE_COLUMN_NAME_KEY;
        }
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


    function getShowPage($showPage,$module){
        if (strlen($showPage)<1 || $showPage == null) $showPage = 15;
        if(strlen($showPage) == 4) $showPage = bindec($showPage);
        $arrShowPages = [];

        if ($showPage & self::ADD) $arrShowPages[] = 'add';
        if ($showPage & self::EDIT) $arrShowPages[] = 'edit';
        if ($showPage & self::LISTS) {
            $arrShowPages[] = 'list';
            //$arrShowPages[] = 'index';
        }
        if ($showPage & self::SEARCH) $arrShowPages[] = 'search';

        if($module == 'home'){
            if(isset($arrShowPages[0]) && $arrShowPages[0] == 'add'){
                $arrShowPages[0] = 'list';
            }

            if(isset($arrShowPages[1]) && $arrShowPages[1] == 'edit'){
                $arrShowPages[1] = 'show';
            }
            if(isset($arrShowPages[2]))  unset($arrShowPages[2]);
            if(isset($arrShowPages[3]))  unset($arrShowPages[3]);
        }
        return $arrShowPages;
    }


    function isLockDefinition(string $tableName){
        $dbName = $this->dbConf['DB_NAME'];
        $sql = "select * from information_schema.tables where table_schema='" . $dbName . "' and table_name='" . '' .  $dbPrefix .$tableName . "'";
        $m = M('');
        $r = $m->query($sql);
        if(empty($r[0]['TABLE_COMMENT'])){
            return false;
        }
        $comment = $r[0]['TABLE_COMMENT'];
        $arr = explode('|',$comment);
        if(!empty($arr[1]) && $arr[1] == 'lock') return true;
        return false;

    }

    function getTableComment(string $tableName){
        $dbName = $this->dbConf['DB_NAME'];
        $sql = "select * from information_schema.tables where table_schema='" . $dbName . "' and table_name='" . '' .  $dbPrefix .$tableName . "'";
        $m = M('');
        $r = $m->query($sql);
        if(empty($r[0]['TABLE_COMMENT'])){
            return false;
        }
        $comment = $r[0]['TABLE_COMMENT'];
        return $comment;
        //$arr = explode('|',$comment);

    }


}
