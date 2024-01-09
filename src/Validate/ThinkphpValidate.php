<?php
namespace Cgf\Validate;
use Cgf\Validate;
class ThinkphpValidate extends Validate
{
    function generateValidate($allColumnConfig){
        $allValidate = [];
        foreach ($allColumnConfig as $column => $config){
            if(empty($config['validate'])) continue;

            foreach ($config['validate'] as $k=>$validate){
                array_unshift($validate,$column);
                $allValidate[] = $validate;
            }
        }
        //var_dump($allValidate);exit;
        return $allValidate;
    }
}