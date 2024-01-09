<?php
namespace Cgf;

class Template {

    function writeTemplate($filename, $content){

        $c = file_get_contents($filename);
        if(strpos($c,'<!--forbid write-->')===false){
            file_put_contents($filename, '<!--forbid- write-->'.$content);
        }

    }
}
