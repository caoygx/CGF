<?php
namespace Cgf;
interface FrameworkInterface{

    /**
     * 获取关联表数组
     * @param string $tableName 关联表名
     * @param array $field 关联表要显示的字段 ['id',"name"]
     * @param array $where 查询条件[$relatedField=>["in",$arrRelatedFieldValues]]
     * @return array 返回关联表数据
     */
    public function getRelatedTableData(string $tableName,array $field,array $where):array;

}