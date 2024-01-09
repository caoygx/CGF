<?php
namespace Cgf;
interface FormInterface{
//    function generateSearchInput($htmlInput,$v);
//    function generateAddInput($htmlInput,$v);
    function text();
    function hidden();
    function password();
    function textarea();
    function editor();
    function select();
    function radio();
    function checkbox();
    function image();
//    function images();
    function file();
//    function files();
//    function datetimePicker($isRange=false,$type='datetime');
//    function datetimeRangePicker();
//    function datePicker();
//    function dateRangePicker();
//    function time();
//    function date();
}