<?php

namespace Cgf\Template\VueElement;

use Cgf\Framework;
use Cgf\FormInterface;
use Cgf\Form;


class VueElementForm extends Form implements FormInterface
{
    public $tplDir = __DIR__ . "/tpl/";

    function __construct()
    {

    }

    /**
     * 根据数据字段类型生成对应的input表单类型
     * @param $name 字段名
     * @param $definition 字段定义
     * @return mixed
     */
    function generate($name, $definition)
    {

        $this->name       = $name;
        $this->definition = $definition;
        //var_dump($definition);//exit;
        /*if($name == 'id'){ //是主键,只能处理命名为id的主键
            $definition['type']='hidden';
        }else*/
        if (empty($definition['type'])) {
            $definition['type'] = 'text';
        }
        //if(empty($definition['type'])) $definition['type']='text';

        return call_user_func_array([$this, $definition['type']], []);
    }

    function generateSearchInput($htmlInput, $v)
    {

        $htmlInput = str_replace('v-model="postForm.','v-model="listQuery.',$htmlInput);
        $htmlInput = str_replace('></el-',' style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" ></el-',$htmlInput);
        $htmlTpl   = file_get_contents($this->tplDir . "/tpl_search_input.html");
        $arrAssign = [
            '{$inputStr}' => $htmlInput,
            '{$tips}'     => "",
            '{$cnName}'   => $v['zh'],
            '{$name}'     => $v['name'],
            '{$type}'     => $v['type'],
        ];
        return str_replace(array_keys($arrAssign), array_values($arrAssign), $htmlTpl);
    }

    function generateAddInput($htmlInput, $v)
    {
        $htmlTpl = file_get_contents($this->tplDir . "/tpl_add_input.html");
        if ($v['type'] == 'hidden') $style = "display: none";
        $arrAssign = [
            '{$inputStr}' => $htmlInput,
            '{$tips}'     => "",
            '{$cnName}'   => $v['zh'],
            '{$name}'     => $v['name'],
            '{$type}'     => $v['type'],
            '{$style}'    => $style,
        ];
        return str_replace(array_keys($arrAssign), array_values($arrAssign), $htmlTpl);
    }

    function generateListLabel($htmlInput, $v)
    {

        if($v['type'] == 'image'){
            $htmlTpl = file_get_contents($this->tplDir . "/tpl_table_column_image.html");
        }else{
            $htmlTpl = file_get_contents($this->tplDir . "/tpl_table_column.html");
        }
        if ($v['type'] == 'hidden') $style = "display: none";
        if($v['type'] == 'select'){
            $v['name'] = $v['show_text'];//select类型，显示时用show_text字段
        }
        $arrAssign = [
            '{$inputStr}' => $htmlInput,
            '{$tips}'     => "",
            '{$cnName}'   => $v['zh'],
            '{$name}'     => $v['name'],
            '{$type}'     => $v['type'],
            '{$style}'    => $style,
        ];
        return str_replace(array_keys($arrAssign), array_values($arrAssign), $htmlTpl);
    }

    function generateValidteRule($v){
        if(empty($v['arrRules'])) return [];
        $rules = $v['arrRules'];
        //validator
        //asyncValidator
        $columnAllRule=[];
        foreach($rules as $k=>$rule){
            $singleRule=[];
            $singleRule['message'] = $rule['msg'];
            $singleRule['trigger'] = 'blur';
            if($rule['type']=='require'){
                $singleRule['required']= true;
            }elseif($rule['type']=='reg'){
                $singleRule['pattern']= $rule['reg'];
            }elseif($rule['type']=='unique'){
//                $singleRule['']
            }
            $columnAllRule[] = $singleRule;
        }
//        $retRule=[];
//        $retRule[$v['name']] = $columnAllRule;

        return $columnAllRule;
//        return json_encode($retRule,JSON_UNESCAPED_UNICODE);

    }



    function text()
    {
        $html = "<el-input v-model=\"postForm.{$this->name}\" placeholder=\"{$this->definition['zh']}\"></el-input>";
        return $html;
    }

    function hidden()
    {
        $html = "<el-input type=\"hidden\" v-model=\"postForm.{$this->name}\" placeholder=\"{$this->definition['zh']}\"></el-input>";
        return $html;
    }

    function password()
    {
        $html = "<el-input show-password v-model=\"postForm.{$this->name}\" placeholder=\"{$this->definition['zh']}\"></el-input>";
        return $html;
    }

    function textarea()
    {

        $html = "<el-input type=\"textarea\" :rows=\"4\" v-model=\"postForm.{$this->name}\" placeholder=\"{$this->definition['zh']}\"></el-input>";
        return $html;

    }

    function editor()
    {
        $name       = $this->name;
        $definition = $this->definition;
        $html       = "<html:editor id=\"editor\" name=\"$name\" type=\"kindeditor\" style=\"width:100%;height:400px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>";
        //$html = "<html:editor id=\"$name\" name=\"$name\" type=\"ueditor\" style=\"width:680px;height:300px;visibility:hidden;\" >" . '{$vo.' . $name . '}' . "</html:editor>";
        return $html;
    }

    //可直接编译为原生php与html
    function select()
    {
        $html = '<el-select v-model="postForm.'.$this->name.'" placeholder="'.$this->definition['zh'].'">
            <el-option
              v-for="item in '.$this->name.'_options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>';

        return $html;
    }

    function radio()
    {
        $html = "<el-radio v-model=\"postForm.{$this->name}\" label=\"1\">{$$this->definition->zh}</el-radio>";
        return $html;
    }

    function checkbox()
    {
        $html = "<el-checkbox v-model=\"postForm.{$this->name}\">{$this->name}</el-checkbox>";
        return $html;
    }

    function image()
    {
        $html = '<el-upload action="http://www.recycle.com/admin/file/upload" :auto-upload="true" list-type="picture-card"
                               :on-change="fileChange"
                               :on-success="function(response, file, fileList) {postForm.'.$this->name.'=response.data.file;}">
                        <i slot="default" class="el-icon-plus"></i>
                    </el-upload>';
        return $html;


        $name = $this->name;
        $html = "<input onchange=\"previewImg(this,'{$name}')\" class=\"avatar-input\" id=\"$name\"  name=\"$name\" type=\"file\">";
        $html .= "";
        $html .= "";

        $html .= "<div id='preview_img_{$name}'>
                                <notempty name=\"vo['$name']\">
                                <img src=" . '"{$vo.' . $name . '|img}"' . " width='100' />
                                 <else />
                                 <img src=" . '"{:config("app.default_image")}"' . " width='100' />
                                 </notempty>
                                 </div>";
        $html .= "";
        //$this->haveImg = true;
        $this->haveUpload = true;
        return $html;
    }

    function images()
    {
    }

    function file()
    {
        $html = '<el-upload action="http://www.recycle.com/admin/file/upload" :auto-upload="true" list-type="text"
                               :on-change="fileChange"
                               :on-success="function(response, file, fileList) {postForm.'.$this->name.'=response.data.file;}">
                        <i slot="default" class="el-icon-plus"></i>
                    </el-upload>';
        return $html;

        $this->haveUpload = true;
        return $html;
    }

    function files()
    {
    }

    /**
     * vue-element 时间控件
     * @param bool $isRange
     * @param string $type 可选值:daterange日期范围，datetimerange时间范围，datetime:日期时间,time:时间,date:日期,month:年月,year:年
     * @return string
     */
    function datetimePicker( $type = 'date',$isRange = false)
    {

        $format='';
        if($type=='date'||$type=='daterange'){
            $format='value-format="yyyy-MM-dd"';
        }
        $name       = $this->name;
        $definition = $this->definition;
//        <span class=\"demonstration\">默认</span>
        $datePicker = "<el-date-picker
                          v-model=\"postForm.{$name}\"
                          type=\"{$type}\"
                          ".$format."
            
                          range-separator=\"至\"
                          start-placeholder=\"{$definition['zh']}起始\"
                          end-placeholder=\"{$definition['zh']}截止\">
                        </el-date-picker>";
        return $datePicker;




        //将属性放入数组，并组成html属性数据，再拼接成html字符串
        $htmlProps = [];
        foreach ($definition as $k => $v) {
            if ($k == "size") {
                $htmlProps['size'] = $v;
            }
            if ($k == "class") {
                $htmlProps['class'] = $v;
            }
        }
        $props = http_build_query($htmlProps);
        $props = str_replace('&', ' ', $props);
        $html  = "<input type='' $props>";

        if (empty($definition['size'])) $definition['size'] = 30;
        $html = '<div class="layui-input-inline">
                        <input type="text" class="layui-input" id="' . $name . '" name="' . $name . '" size="' . $definition['size'] . '" placeholder=" - "
                               value="{$Think.get.create_t}">
                    </div>';


        if ($isRange) {
            $range = 'range: true';
        }
        $html .= "
        <script>
            laydate.render({
                elem: '#{$name}', 
                type: '{$type}',
                {$range}

            });
        </script>";
        return $html;

    }

    function datetimeRangePicker()
    {
        return $this->datetimePicker('datetimerange');
    }

    function datePicker()
    {
        return $this->datetimePicker('date');
    }

    function dateRangePicker()
    {
        return $this->datetimePicker('daterange');
    }

    function time()
    {
        return $this->datetimePicker('datetime');
    }

    function date()
    {
        return $this->datetimePicker('date');
    }


}
