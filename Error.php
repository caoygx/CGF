<?php
namespace app\controller;
use Cgf\Framework\Thinkphp\BaseController;

class Error extends BaseController
{
    public function __call($method, $args)
    {
        return 'error request!';
    }
}
