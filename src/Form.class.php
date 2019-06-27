<?php
namespace Cgf;
class Form{
    function __construct()
    {

    }

    function generate($name,$definition){
        $this->name = $name;
        $this->definition = $definition;
        //var_dump([this,$definition['type']]);exit;

        return call_user_func_array([$this,$definition['type']],[]);
    }


    function text(){
/*        ["search" => [
            "act_goods_name" => [
                "type" => "text",
                "size" => 10,
                "validation"=>"mobile-unique",
            ],
            "content" => [
                "type" => "editor",
                "size" => 10,
                "component"=>"fck",//编辑器组件 kindeditor
            ],
            "create_t" => [
                "type" => "datetimePicker",//时间
                "format" => "y-m-d H:i:s",
            ],
            "draw_state" => [
                "type" => "select",
                "options" => [
                    0 => "未中奖",
                    1 => "已中奖"
                ],
            ],
            "memberno" => [
                "type" => "text",
            ]
        ]
        ];*/

        $name = $this->name;
        $definition = $this->definition;

        $html = "<input {$definition['validation']} class=\"form-control\" name=\"$name\" type=\"text\" id=\"$name\" size=\"{$definition['size']}\" value=" . '"{$vo[' . $name . '] ? $vo[' . $name . '] : $_GET[' . $name . ']}"' . " />";
        return $html;
        //return "<input ty"

    }

    function hidden(){
        $name = $this->name;
        $html = "<input  type=\"hidden\"  class=\"form-control\" name=\"$name\" id=\"$name\"  value=" . '"{$vo.' . $name . '}"' . " />";
        return $html;
    }

    function password(){
        $name = $this->name;
        $definition = $this->definition;
        $html = "<input {$this->definition['validation']}  type=\"password\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$definition['size']['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
        return $html;
    }

	function textarea(){
        $name = $this->name;
        $html = "<textarea rows=\"5\" {$this->definition['validation']}  class=\"form-control\" name=\"$name\"  id=\"$name\">" . '{$vo.' . $name . '}' . "</textarea>";
        return $html;

    }

	function editor(){
        $name = $this->name;
        $definition = $this->definition;
        $html = "<html:editor id=\"editor\" name=\"$name\" type=\"kindeditor\" style=\"width:680px;height:300px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>";
        return $html;
    }

    //可直接编译为原生php与html
	function select(){
        $name = $this->name;
        $html = "<html:select  $first options='opt_{$name}' selected='{$name}_selected' name=\"{$name}\" />";
        return $html;
    }
	function radio(){}
	function checkbox(){
        $name = $this->name;
        $html .= "<html:checkbox checkboxes='opt_{$name}' checked='{$name}_selected' name='{$name}' />";
        return $html;
    }
	function iamge(){}
	function images(){}
	function file(){}
	function files(){}
	function datePicker(){}
	function datetimePicker(){
        $name = $this->name;
        $definition = $this->definition;

        //将属性放入数组，并组成html属性数据，再拼接成html字符串
        $htmlProps = [];
        foreach ($definition['props'] as $k=>$v){
            if($k == "size"){
                $htmlProps['size'] = $v;
            }
            if($k == "class"){
                $htmlProps['class'] = $v;
            }
        }
        $props = http_build_query($htmlProps);
        $props = str_replace('&',' ',$props);
        $html = "<input type='' $props>";


        $html = '<div class="layui-input-inline">
                        <input type="text" class="layui-input" id="'.$name.'" name="'.$name.'" size="'.$definition['size'].'" placeholder=" - "
                               value="{$Think.get.create_t}">
                    </div>';
        return $html;

    }
    function dateRangePicker(){}
    function datetimeRangePicker(){}

}