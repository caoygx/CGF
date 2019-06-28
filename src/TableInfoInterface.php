<?php
namespace Cgf;
interface TableInfoInterface{

    /**
     * 获取表所有字段的定义
     * @param $tableName
     * @return array
     */
    public function getAllColumnDefinition(string $tableName);


    /**
     * 获取是否锁定配置文件，若锁定，则配置不能更改
     * @return mixed
     */
    public function isLockDefinition(string $tableName);

    /**
     * 获取表自身注释
     * @param string $tableName
     * @return mixed
     */
    public function getTableComment(string $tableName);
}