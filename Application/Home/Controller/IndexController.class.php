<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    function index(){
		$this->display();
	}
	function test(){
        $m = M('doc');
        $r = $m->select();
        $this->display();
    }


}