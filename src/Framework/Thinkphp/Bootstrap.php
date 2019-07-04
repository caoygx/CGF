<?php
namespace Cgf\Form;
use Cgf\Form;


class Bootstrap extends Form {
    //public $tplDir="";
    public $tplDir = __DIR__."/Bootstrap";
    function __construct()
    {

    }

    function generate($name,$definition){
        $this->name = $name;
        $this->definition = $definition;
        var_dump($definition);//exit;
        /*if($name == 'id'){ //是主键,只能处理命名为id的主键
            $definition['type']='hidden';
        }else*/
        if(empty($definition['type'])){
            $definition['type']='text';
        }
        //if(empty($definition['type'])) $definition['type']='text';

        return call_user_func_array([$this,$definition['type']],[]);
    }

    function generateSearchInput($htmlInput,$v){
        $htmlTpl = file_get_contents($this->tplDir."/tpl_search_input.html");
        $arrAssign = [
            '{$inputStr}' => $htmlInput,
            '{$tips}' => "",
            '{$cnName}' => $v['zh'],
            '{$name}' => $v['name'],
            '{$type}' => $v['type'],
        ];
        return str_replace(array_keys($arrAssign),array_values($arrAssign),$htmlTpl);
    }

    function generateAddInput($htmlInput,$v){
        $htmlTpl = file_get_contents($this->tplDir."/tpl_add_input.html");
        if($v['type'] == 'hidden') $style="display: none";
        $arrAssign = [
            '{$inputStr}' => $htmlInput,
            '{$tips}' => "",
            '{$cnName}' => $v['zh'],
            '{$name}' => $v['name'],
            '{$type}' => $v['type'],
            '{$style}' => $style,
        ];
        return str_replace(array_keys($arrAssign),array_values($arrAssign),$htmlTpl);
    }


    function text(){

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
        $html = "<input {$this->definition['validation']}  type=\"password\"  class=\"form-control\" name=\"$name\" id=\"$name\" size=\"{$definition['size']}\" value=" . '"{$vo.' . $name . '}"' . " />";
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
        $html = "<html:editor id=\"editor\" name=\"$name\" type=\"kindeditor\" style=\"width:100%;height:400px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>";
        //$html = "<html:editor id=\"$name\" name=\"$name\" type=\"ueditor\" style=\"width:680px;height:300px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>";
        return $html;
    }

    //可直接编译为原生php与html
	function select(){
        $name = $this->name;
        $first ='first="请选择"';
        $html = "<html:select  $first options='opt_{$name}' selected='{$name}_selected' name=\"{$name}\" />";
        //echo $html;exit('select');
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

    /**
     * layui 时间控件
     * @param bool $isRange
     * @param string $type  可选值:datetime:日期时间,time:时间,date:日期,month:年月,year:年
     * @return string
     */
	function datetimePicker($isRange=false,$type='datetime'){
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

if(empty($definition['size'])) $definition['size'] = 30;
        $html = '<div class="layui-input-inline">
                        <input type="text" class="layui-input" id="'.$name.'" name="'.$name.'" size="'.$definition['size'].'" placeholder=" - "
                               value="{$Think.get.create_t}">
                    </div>';


        if($isRange){
            $range = 'range: true';
        }
        $html .="
        <script>
            laydate.render({
                elem: '#{$name}', 
                type: '{$type}',
                {$range}

            });
        </script>";
        return $html;

    }

    function datetimeRangePicker(){
        return $this->datetimePicker(true);
    }

    function datePicker(){
        return $this->datetimePicker(false,'date');
    }

    function dateRangePicker(){
        return $this->datetimePicker(true,'date');
    }

    function time(){
        return $this->datetimePicker();
    }

    function date(){
        return $this->datetimePicker(false,'date');
    }




}