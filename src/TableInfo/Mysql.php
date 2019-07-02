<?php
namespace Cgf\TableInfo;
use Cgf\TableInfoInterface;


/**
 * mysql表信息获取类，各框架需自己实现 TableInfoInterface接口
 * Class MysqlTableInfo
 * @package Common\Cgf
 */
class Mysql implements TableInfoInterface
{

    public $db;
    //public $dbConfig;
    public $dbname;

    /**
     * TableInfo constructor.
     */
    function __construct(array $dbConfig, string $tableName)
    {

        //$this->dbConfig = $dbConfig;
        $this->dbname = $dbConfig['dbname'];
        $this->tableName = $tableName;

        $dbms='mysql';     //数据库类型
        $host=$dbConfig['host'];
        $dbname=$dbConfig['dbname'];   
        $username=$dbConfig['username'];
        $password=$dbConfig['password'];
        $dsn="$dbms:host=$host;dbname=$dbname";

        try {
           
            $this->db = new \PDO($dsn, $username, $password); //初始化一个PDO对象

        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }




    /**
     * 获取表定义
     * @param $tableName
     * @return array
     */
    public function getTableDefinition(string $tableName)
    {
        $sql = "select * from information_schema.columns where table_schema='{$this->dbname}' and table_name='{$tableName}' ";
        //echo $sql;exit;
        $query = $this->db->query($sql);
        $result = $query->fetchAll();
        return $result;
    }

    function getTableComment(string $tableName){
        $sql = "select * from information_schema.tables where table_schema='{$this->dbname}' and table_name='{$tableName}' ";
        $query = $this->db->query($sql);
        $result = $query->fetchAll();
        if(empty($result[0]['TABLE_COMMENT'])){
            return false;
        }
        $comment = $result[0]['TABLE_COMMENT'];
        return $comment;
        //$arr = explode('|',$comment);

    }


    function isLockDefinition(string $tableName){
        $comment = $this->getTableComment($tableName);
        $arr = explode('|',$comment);
        if(!empty($arr[1]) && $arr[1] == 'lock') return true;
        return false;

    }






































    /**
     * 根据数据库类型获取列名键
     * @return mixed
     */
    public function getColumnNameKey()
    {

        return MYSQL_COLUMN_NAME_KEY;

    }


    /**
     * 获取表名列表
     * @return array
     */
    public function getTableNameList()
    {
        $dbType = $this->dbConf['DB_TYPE'];
            $dbName = $this->dbConf['DB_NAME'];
            $result = Array();
            $tempArray = $this->db->query("select table_name from information_schema.tables where table_schema='" . $dbName . "' and table_type='base table'");
            foreach ($tempArray as $temp) {
                $result[] = $temp['table_name'];
            }
            return $result;

        $this->error('数据库类型不支持');
    }



}
