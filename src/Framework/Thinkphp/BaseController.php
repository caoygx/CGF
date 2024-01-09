<?php


namespace Cgf\Framework\Thinkphp;

use Cgf\Cgf;
use think\App;
use think\exception\InvalidArgumentException;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;
use think\helper\Str;
use think\facade\Console;
use think\exception\ValidateException;
use think\Model;
use think\Validate;

//use liliuwei\think\Jump;


/**
 * 控制器基础类
 */
abstract class BaseController
{

    public $u_id;
    public $user;

    /**
     * @var Cgf
     */
    public $cgf;

    /** @var  Model */
    public $m;
    public $moduleName;
    public $controllerName;
    public $actionName;
    public $pageVar = [];
    public $autoInstantiateModel = true;
    public $allowUpdateFields = ["status"];
    public $modelTemplate = '<?php
namespace {%namespace%};

/**
 * @mixin think\Model
 */
class {%className%} extends Common
{
    //
}
';
    /**
     * @var 前台用户id标识
     */
    public $front_user_id = 'user_id';

    /**
     * 后台用户id标识
     * @var
     */
    public $backend_user_id = 'admin_id';

    protected $user_id = 0;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    protected $routeType = "";


    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;
        if ($this->request->user_id) {
            $this->user_id = $this->request->user_id;
            $this->user = $this->request->user;
        } elseif ($this->request->admin_id) {
            $this->admin_id = $this->request->admin_id;
            $this->admin = $this->request->admin;
        }

        // 控制器初始化
        $this->initialize();
    }


    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }


    function makeModelFile($name)
    {
        $stub = $this->modelTemplate;
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        $modelContent = str_replace(['{%className%}', '{%namespace%}', '{%app_namespace%}'], [
            $class,
            $namespace,
            $this->app->getNamespace(),
        ], $stub);

        $name = str_replace('app\\', '', $name);

        $pathname = $this->app->getBasePath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';
        file_put_contents($pathname, $modelContent);

    }

    //旧方法，运行不起来
    function createModelAutomatically2($modelName)
    {
        $modelDir = $this->getModelDir();
        /*if($this->routeType == "micro_module_class"){
            $className = '\\app\\model\\' . $modelName;
        }else{
            $className = '\\muser\\model\\';
        }*/
        $className = $modelDir . "\\" . $modelName;

        //if (!class_exists($className)) {
        //$this->makeModelFile('app\\model\\'.$modelName);//exit;
        //Console::call('make:model', ['app\\model\\' . $modelName]);
        //}
        $this->m = app($className);
        //$this->m = new $className();

    }

    function createModelAutomatically($modelName)
    {
        $modelDir = '';
        if (method_exists($this, 'getModelDir')) {
            $modelDir = $this->getModelDir();
        }
        if (empty($modelDir)) {
            $modelDir = '\\app\\model';
        }
        $className = $modelDir . "\\" . $modelName;
        if (!class_exists($className)) {
            //$this->makeModelFile('app\\model\\'.$modelName);//exit;
            Console::call('make:model', ['app\\model\\' . $modelName]); //目前只能在app目录下生成model
        }
//        var_dump($className);exit('x')
        $this->m = app($className);
//        var_dump($this->m);exit;
        //$this->m = new $className();

    }

    function createModelForMicroModule()
    {
        $className = '\\app\\model\\' . $modelName;

    }


    // 初始化
    protected function initialize()
    {
        //$route       = $this->app->request->rule()->getRoute();
        $route = $this->app->request->rule()->getName();
        if (false !== strpos($route, '\\')) { //类路由 \mnews\controller\News@index
            $this->routeType = "micro_module_class";
            $vendorRoute = substr($route, strrpos($route, '\\') + 1);
            $route = str_replace('@', '/', $vendorRoute);
        }
        //if()
        //$delimiter = \think\helper\Str::contains($route, '@') ? '@' : '/';

        //$route = explode(\think\helper\Str::contains($route, '@') ? '@' : '/', $route);
        if (empty($route)) {
            $route = $this->app->request->pathinfo();
        }
        /* if($route){

         }else{
             $route = $this->app->request->pathinfo();
         }*/

        $arrRoute = explode('/', $route);
        $controllerName = ucfirst($arrRoute[0]);
        $actionName = $arrRoute[1];
        //var_dump(Request::isAjax());exit;
        define('CONTROLLER_NAME', $controllerName);
        define('IS_AJAX', Request::isAjax());
        define('IS_GET', Request::isGet());
        define('IS_POST', Request::isPost());
        define('URL_IMG', '/storage');
        //var_dump(CONTROLLER_NAME);exit;

        $this->moduleName = app('http')->getName();
        $this->moduleName = 'index';
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        //var_dump($this->moduleName,$this->controllerName,$this->actionName);exit;

        $this->assign('controllerName', $this->controllerName);
        $this->assign('actionName', $this->actionName);

        if ($this->autoInstantiateModel) {
            $this->createModelAutomatically($this->controllerName);
        }

        //cgf相关
        if ($this->autoInstantiateModel) {
            $tableName = Str::snake($this->controllerName, '_');

            //$this->m = Db::name($this->controllerName);

            error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT ^ E_WARNING);
            $appBasePath = $this->app->getAppPath();
            $dbconfig = include('../config/database.php');
            $dbconfig = $dbconfig['connections']['mysql'];
            //dump(Cgf::$config);exit;
            $dbconfig = Cgf::getDbConfigFromThinkPHP($dbconfig); //将tp6 db配置转成cgf的配置

            $cgfConf = [];
            $cgfConf['dbConfig'] = $dbconfig;
            $cgfConf['savePath'] = $appBasePath . "/cgf/definition";//保存cgf生成的定义文件
            $cgfConf['framework'] = 'thinkphp';//使用的框架
            $cgfConf['validate'] = 'vueElement';//使用验证库
            $cgfConf['template'] = 'vueElement';//表单使用的框架
            $cgfConf['currentName'] = 'common';//当前模块名
            $cgfConf['tableName'] = $tableName;//表名
            $cgfConf['controllerName'] = $this->controllerName;//控制器名
            $cgfConf['appRootPath'] = $appBasePath;//框架应用程序根目录

            $viewDir = $this->app->getAppPath() . "view/" . $this->request->module . "/";
            $cgfConf['parentTemplatePath'] = $viewDir . 'public/';//cgf生成模板使用的父模板,cgf会根据这里的模板来生成应用模板
            $cgfConf['templateSavePath'] = $viewDir . "{$this->controllerName}";//cgf生成的模板保存路径
            $cgfConf['availableModule'] = ['common', 'admin', 'index'];//可用模块
            $cgfConf['autoHiddenPrimaryKey'] = false;//是否将主键表单类型设为hidden

            $this->cgf = new Cgf($cgfConf);
            //$this->cgf->setTemplate('vueElement');
        }
        if (empty($this->allowUpdateFields) && !empty(config('app.allowUpdateFields')[$this->controllerName])) {
            $this->allowUpdateFields = config('app.allowUpdateFields')[$this->controllerName];
        }
    }

    public function index()
    {
        if (method_exists($this, '_befor_index')) {
            $this->_befor_index($this->m);
        }
        $where = $this->whereCondition();

        //手工处理自动生成的where条件
        if (method_exists($this, '_filter')) {
            $this->_filter($this->m);
        }

//        $r = $this->cgf->generateListsTemplate();//生成模板
        if (!empty ($this->m)) {
            $r = $this->pageList($this->m, $where);
            return $this->toview($r);
        }

    }


    function edit()
    {

        //自动获取添加模板
        $tpl = $this->cgf->generateAddTemplatel(true);

        $this->generateOptions();

        $id = input($this->m->getPk());
        $where = [];
        $where['id'] = $id;
        if ($this->request->module == 'User') $where['user_id'] = $this->user_id;
        $vo = $this->m->where($where)->find();
        if (method_exists($this, '_replacePublic')) {
            $this->_replacePublic($vo);
        }
        //var_dump($vo);exit;
        $this->assign('vo', $vo);
        $this->assign('action', 'edit');

        $this->pageTitle = $this->getControllerTitle(CONTROLLER_NAME) . "编辑";
        $this->cgf = "cgf";

        return $this->toview("", "add");
    }

    function generateOptions()
    {
        $options = $this->cgf->definition->getAllColumnOptions();
        foreach ($options as $column => $option) {
            $this->assign('opt_' . $column, $option);
            $this->assign($column . '_selected', input($column));
        }
    }


    function save()
    {
        $data = input();
        if (method_exists($this, "_before_save")) {
            $this->_before_save($data);
        }
        if ($this->user_id) {
            $data['user_id'] = $this->user_id;
        }

        $pk = $this->m->getPk();
        $id = $data[$pk];
        //编辑时，验证信息所有者权限
        if (!empty($id)) {
            $rModel = $this->m->where([$this->m->getPk() => $id])->find(); //, "store_id" => $this->store_id
            //if (empty($rModel)) return $this->error('没有所有者权限');
        }

        //验证
        if (method_exists($this, '_validateSave')) {
            $rValidate = $this->_validateSave($this->m);
            if ($rValidate !== true) {
                return $this->error($rValidate);
            }
        }

        //保存
        if (empty($data[$pk])) {
            $r = $this->m->save($data);
        } else {
            $r = $rModel->save($data);
        }

        if ($r === false) {
            return $this->error();
        }

        $id = $this->m->id;
        if (!empty($id)) $this->assign('id', $id);

        if (method_exists($this, "_after_save")) {
            $this->_after_save($id);
        }

        return $this->toview();
        //return $this->success();
        //return $this->toview();


        /* //自动验证
         //$tableInfo = new TableInfo('',$this->dbConnection);
         $validate = new ThinkphpValidate();
         $this->cgf->setValidate($validate);
         $tableName = $this->m->getTableName();
         $rules     = $this->cgf->generateValidate($tableName);
         //$auto = $tableInfo->getAutoComplete($tableName);
         $data = $this->m->create();

         if (empty($id)) {
             $isNew = 1;
             unset($_POST['id']);
             //$r  = $this->m->validate($rules)->create ();
             //$_POST['uid'] = $this->uid; //添加时默认加上用户id
             if (false === $this->m->validate($rules)->create()) {
                 //$this->error ( $this->m->getError () );
             }
             $r  = $this->m->add();
             $id = $r;

         } else {
             if (false === $this->m->validate($rules)->create()) {
                 $this->error($this->m->getError());
             }
             $r = $this->m->save();
         }

         //保存当前数据对象
         if (method_exists($this, '_after_save') && $id) $this->_after_save($id);

         //echo $this->m->getLastSql();exit;
         if ($id !== false) { //保存成功
             $ret = [];
             //$this->assign ( 'jumpUrl', cookie( '_currentUrl_' ) );

             if (input('multiple')) { //多图，需要关联图片到对应的主题,
                 //注意，不支持一个主题里有两个以上字段都是多图上传。此情况需要在图片表里增加标识，属于哪个字段，
                 //或在主题字段里存储用逗号分隔的图片id,如:1,2,3

                 //其实不用forech,直接取$uploadInfo[0]即可，反正又不支持多字段多图上传
                 if (!empty($uploadInfo)) {
                     foreach ($uploadInfo as $k => $v) {
                         $ids[$k] = array_column($v, 'id');
                         M('File')->where(['id' => ['in', $ids[$k]]])->setField('tid', $id);

                     }

                     $ret = $uploadInfo;
                 }

             }


             if ($this->successRedirectUrl) {
                 $redirectUrl = $this->successRedirectUrl . $id;
             } elseif ($isNew && $this->newInfoNextUrl) {
                 $redirectUrl = $this->newInfoNextUrl . $id;
             } else {
                 //$redirectUrl = cookie( '_currentUrl_' );
                 $redirectUrl = "/" . CONTROLLER_NAME;
             }
             C('info_id', $id);
             $ret['id'] = $id;
             $this->success($ret, '成功', $redirectUrl);
         } else {
             //失败提示
             $this->error('失败了!');
         }*/
    }

    /**
     * 所有者验证，用户表相关操作由userController重写的函数来验证
     * @param $infoId 信息主键值,一般为id的值
     * @param $user_id 用户id
     * @return bool
     */
    function ownerVerify($id, $user_id)
    {
        $r = $this->m->where([$this->m->getPk() => $id, "uid" => $user_id])->find();
        if (empty($r)) {
            $this->error('没有所有者权限');
        }
    }

    public function show()
    {
        $param = input("");
        if (method_exists($this, '_before_show')) {
            $this->_before_show($param);
        }

        $id = $param['id'];
        if (empty($id)) {
            return "参数id不能为空";
        }
        $vo = $this->m->find($id);
        if (empty($vo)) return $this->error('数据不存在');
        $vo = $vo->toArray();

        $this->cgf->standardizeRow($vo);
        if (method_exists($this, '_show')) {
            $this->_show($vo);
        }

        $this->assign('vo', $vo);
        return $this->toview();
    }

    //用户一对一的记录显示
    public function info()
    {
        $param = input("");
        if (method_exists($this, '_before_show')) {
            $this->_before_show($param);
        }

        $vo = $this->m->where('user_id', $this->user_id)->find();
//        $vo = $this->m->find($id);
        if (empty($vo)) return $this->error('数据不存在');
        $vo = $vo->toArray();

        $this->cgf->standardizeRow($vo);

        if (method_exists($this, '_show')) {
            $this->_show($vo);
        }

        $this->assign('vo', $vo);
        return $this->toview();
    }

    //用户一对一的记录保存
    function infoSave()
    {
        $data = input();
        if (method_exists($this, "_before_save")) {
            $this->_before_save($data);
        }

        $pk = $this->m->getPk();
        $id = $data[$pk];
        $rModel = $this->m->where(['user_id' => $this->user_id])->find(); //, "store_id" => $this->store_id


        //处理上传
        if (haveUploadFile()) {
            $uploadInfo = $this->commonUpload();
            if (!empty($uploadInfo)) {
                $data = array_merge($data, $uploadInfo);
            }
        }

        //验证
        if (method_exists($this, '_validateSave')) {
            $rValidate = $this->_validateSave($this->m);
            if ($rValidate !== true) {
                return $this->error($rValidate);
            }
        }

        //保存
        if (empty($rModel)) { //添加
            $data['user_id'] = $this->user_id;
            $r = $this->m->save($data);
        } else {
            $r = $rModel->where("user_id", $this->user_id)->save($data); //编辑
        }
        if ($r === false) {
            return $this->error();
        }
        $id = $this->m->id;
        if (!empty($id)) $this->assign('id', $id);
        if (method_exists($this, "_after_save")) {
            $this->_after_save($id);
        }
        return $this->toview();
    }


    function getIds()
    {
        $ids = input('id');
        return explode(',', $ids);
    }

    function enableField($type)
    {
        $ids = $this->getIds();
        return $this->switchFieldState($ids, $type, 1);
    }

    function disableField($type)
    {
        $ids = $this->getIds();
        return $this->switchFieldState($ids, $type, 0);
    }

    /**
     * to update column value,generally this type of value of column is number or enumeration
     * @param $ids
     * @param $filed
     * @param $value
     * @return member|array|\think\response\Json|\think\response\Jsonp
     */
    protected function switchFieldState($ids, $filed, $value)
    {
        if (!in_array($filed, $this->allowUpdateFields)) return $this->error('非法类型操作');

        $rAuth = $this->verifyOwnerPermission($ids);
        if ($rAuth !== true) return $rAuth; //没有权限提前返回

        $r = $this->m->where(['id' => $ids])->update([$filed => $value]);
        if (empty($r)) return $this->error('更新失败');
        return $this->toview();
    }

    function upload($stype = 'file')
    {
        $uploadInfo = $this->commonUpload($stype);
        $this->upload = $uploadInfo;
        //$this->assign("data",$uploadInfo);
        return $this->toview($uploadInfo);
    }

    function commonUpload($moduleDir = 'file')
    {
        $imageInfoOfSaved = [];
        $files = request()->file('');
        foreach ($files as $name => $file) {
            if (empty($moduleDir)) $moduleDir = $name;
            if (is_array($file)) {
                foreach ($file as $k => $v) {
                    $imageInfoOfSaved[$name][] = \think\facade\Filesystem::disk('public')->putFile($moduleDir, $v);
                }
            } else {
                $imageInfoOfSaved[$name] = \think\facade\Filesystem::disk('public')->putFile($moduleDir, $file);
                $imageInfoOfSaved[$name . "_url"] = img(\think\facade\Filesystem::disk('public')->putFile($moduleDir, $file));
                //exit('x');
            }
        }
        return $imageInfoOfSaved;

        //return commonUpload($moduleDir);
    }

    function verifyOwnerPermission($id)
    {
        $pk = $this->m->getPk();
        //验证编辑保存权限
        if ($this->request->module == "u") {
            $condition = array($pk => explode(',', $id), "user_id" => $this->user_id);
        } elseif ($this->request->module == "admin") {
            return true;
            //$condition = array($pk => explode(',', $id));
        }
        if (!empty($id)) { //编辑保存验证
            $rModel = $this->m->where($condition)->find();
            if (!empty($rModel)) {
                return true;
            } else {
                return $this->error('没有所有者权限');
            }
        }
    }

    public function delete()
    {
        $pk = $this->m->getPk();
        $id = input($pk);
        $id = (string)$id;
        if (empty ($id)) return $this->error('非法操作');

        $rAuth = $this->verifyOwnerPermission($id);
        if ($rAuth !== true) return $rAuth; //没有权限提前返回

//        $rDelete = $this->m->where("id",$id)->delete();
        // 软删除
        $rModel = $this->m->find($id);
        if (empty($rModel)) {
            return $this->error('删除失败');
        }
        $rDelete = $rModel->delete();
        if (empty($rDelete)) return $this->error('删除失败');
        return $this->toview();

        /*if (!empty(input('callback')) || IS_AJAX){
            $method_name = '_after_' . ACTION_NAME;
            if(method_exists($this,$method_name)){
                $this->$method_name($r);
            }
        }*/
    }

    public function logicalDelete()
    {
        $pk = $this->m->getPk();
        $id = input($pk);
        if (empty ($id)) return $this->error('非法操作');

        $condition = array($pk => explode(',', $id));
        $rDelete = $this->m->where($condition)->update(['status' => 0]);
        if (empty($rDelete)) return $this->error('删除失败');
        return $this->toview();
    }


    public function add()
    {
        $tpl = $this->cgf->generateAddTemplatel();

        //配置select选项和选中值
        $allColumnOptionsDefinition = $this->cgf->getAllColumnOptions();
        foreach ($allColumnOptionsDefinition as $column => $option) {
            $this->assign('opt_' . $column, $option);
            $this->assign($column . '_selected', input($column, ''));
        }

        /*if (method_exists($this, '_replacePublic')) {
            $this->_replacePublic($vo);
        }

        $this->pageTitle = $this->getControllerTitle(CONTROLLER_NAME) . "添加";
        $this->cgf       = "cgf";*/
        return $this->toview();
    }


    function _initialize()
    {

        //由于 Think\controller construct 里先调用了_initialize ，
        // 但userBase 验证登录是放在_initialize,导致此类construct 还没执行，就被跳转了
        // 跳转代码获取不到ret_format 导出返回信息仍是html 跳转
        //所以要此代码从construct 移动到此处


        /*//白名单优先
        if(!empty(C('whitelist'))) {
            //只允许白名单中的controller
            $wl_control = C('whitelist.controller');
            $wl_action = C('whitelist.action');
            $wl_url = C('whitelist.url');
            $wl_url = array_map(
                function($value) {
                    return strtolower($value);
                },
                $wl_url
            );
            $current_url = CONTROLLER_NAME . '/' . ACTION_NAME;
            //var_dump(lcfirst($current_url));
            //var_dump($wl_url);exit;
            if (in_array(lcfirst(CONTROLLER_NAME), $wl_control)
                || in_array(lcfirst(ACTION_NAME), $wl_action)
                || in_array(strtolower($current_url), $wl_url)

            ) {
                //通过白名单
            } else {
                return $this->error('control 1 非法访问');
            }
        }

        //黑名单
        if(!empty(C('blacklist'))){
            //禁用访问黑名单中的controller
            $bl_control = C('blacklist.controller');
            $bl_action = C('blacklist.action');
            $bl_url = C('blacklist.url');
            $current_url = CONTROLLER_NAME.'/'.ACTION_NAME;
            if( in_array(lcfirst(CONTROLLER_NAME),$bl_control)
                || in_array(lcfirst(ACTION_NAME),$bl_action)
                || in_array(strtolower($current_url),$bl_url)
            ){
                return $this->error('control 2 非法访问');
            }

        }*/


    }


    function getAuth()
    {
        $u = getUserAuth();
        if (empty($u)) return [];

        //$dbUserInfo = M('User')->field($selectFields)->find($u['uid']);
        $dbUserInfo = M('OutletUser')->find($u['uid']);
        if (empty($dbUserInfo)) {
            debug('没有此用户');
            //echo '没有此用户';
            return false;
        }

        //$dbUserInfo['nickname'] =  !empty($dbUserInfo['nickname']) ? $dbUserInfo['nickname'] : ( !empty($dbUserInfo['username']) ? $dbUserInfo['username'] :  '竞拍用户') ;
        //$dbUserInfo['avatar'] = img($dbUserInfo['avatar'],'user_avatar');
        $this->uid = $dbUserInfo['id'];
        $this->user = $dbUserInfo;
        $this->assign('uid', $this->uid);
        $this->assign('user', $this->user);
        return $dbUserInfo;
    }


    public function lists()
    {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $name = CONTROLLER_NAME;
        //$model = D ($name);
        if (!empty ($this->m)) {
            $this->_list($this->m, $map);
        }
        $this->toview();
        return;
        //exit('lists erorr');
        // $this->display ();
        //return;
    }


    protected function _search_array($tableName = '')
    {
        $map = [];
        $autoIndistinct = true;
        //var_dump($this->controllerName);exit;

        if (!$tableName) $tableName = Str::snake($this->controllerName, '_');
        $fields = Db::name($tableName)->getFields(); //包含字段类型，注释等
        foreach ($fields as $column => $definition) {
            $inputValue = input($column);
            if ($inputValue !== null && $inputValue != '') {
                if ($autoIndistinct && $this->columnType($definition['type']) == 'string') {
                    $map[] = [$column, 'like', '%' . $inputValue . '%'];
                } else {
                    $map[] = [$column, '=', $inputValue];
                }
            }
        }

        //配置select选项和选中值
        /* $options = $this->cgf->getAllColumnOptions();
         foreach ($options as $column => $option) {
             $this->assign('opt_' . $column, $option);
             $this->assign($column . '_selected', input($column,[]));
             //var_dump($column . '_selected');
         }*/

        return $map;

    }

    /**
     * 处理搜索条件
     * @param array $requestParam
     */
    protected function whereCondition($requestParam = [])
    {
        if (empty($requestParam)) $requestParam = input();
        //$requestParam['uid'] = $this->request->uid;
        $front_user_id = config("cgf.front_user_id");//$this->front_user_id;
        $backend_user_id = config("cgf.backend_user_id");//$this->backend_user_id;

        if ($this->request->module == "u") { //用户中心默认增加user_id条件
            $requestParam[$front_user_id] = $this->request->$front_user_id;
        }
        if (!empty($this->removeUserId)) {
            unset($requestParam[$front_user_id]);
        }
        $requestParam[$backend_user_id] = $this->request->$backend_user_id;
        $autoIndistinct = true;
        $tableName = Str::snake($this->controllerName, '_');
        //var_dump($this->m->getFields());exit;
        $fields = Db::name($tableName)->getFields(); //包含字段类型，注释等
        $where = [];
        foreach ($fields as $column => $definition) {
            $inputValue = $requestParam[$column];
            if ($inputValue !== null && $inputValue != '') {
                if ($autoIndistinct && $this->columnType($definition['type']) == 'string') {
                    $where[] = [$column, 'like', '%' . $inputValue . '%'];
                } else {
                    $where[] = [$column, '=', $inputValue];
                }
            }
        }
        $this->_option();
        return $where;
    }

    /**
     * 处理select之类的枚举类的选项对应
     */
    protected function _option()
    {
        //var_dump($this->m);exit;
        //配置select选项和选中值
        $options = $this->cgf->getAllColumnOptions();
        foreach ($options as $column => $option) {
            $this->assign('opt_' . $column, $option);
            $this->assign($column . '_selected', input($column, []));
        }
    }

    function columnType($type)
    {
        if (strpos($type, 'char') !== false || strpos($type, 'text') !== false) {
            return 'string';
        } elseif (strpos($type, 'int') !== false || strpos($type, 'decimal') !== false || strpos($type, 'numeric') || strpos($type, 'double')) {
            return 'int';
        } else {
            return '';
        }
    }

    /**
     * 根据表单生成查询条件
     * 进行列表过滤
     * @param $model 模型对象
     * @param $map 过滤条件
     * @param string $sortBy 排序
     * @param bool $asc 是否正序
     */
    protected function pageList($model, $where = [], $order = '', $sort = '', $limit = 0)
    {

        //排序字段
        if (empty($order)) $order = input('_order');
        if (empty($order)) $order = $model->getPk();

        //排序方式
        if (empty($sort)) $sort = input('_order');
        if (empty($sort)) $sort = 'desc';

        if (empty($limit)) $limit = input('_limit');
        if (empty($limit)) $limit = 10;


        $count = $model->where($where)->count();
        if ($count <= 0) return [];

        //========================================== cgf  start =========================================

        //1.生成查询字段
        $selectFields = $this->cgf->generateListSelectColumn();


        //关联表预加载
        $preloadMethod = 'join';
        if ($model->preloadTable) {
            foreach ($model->preloadTable as $k => $v) {
                if ($preloadMethod == 'join') {
                    $model = $model->withJoin($v);
                } else {
                    $model->with($v);
                }
            }
        }
        $voList = $model->where($where)->field($selectFields)->order($order . ' ' . $sort)->paginate($listRows, false, ['query' => []]);

        //去掉前台不显示的字段，与上面$selectFields功能重复
        /*if(C('ret_format') == 'json' || C('ret_format') == 'jsonp'){
            $tableInfo = new TableInfo('list');
            $tableName = $this->m->getTableName();
            $fields = $tableInfo->generateHomeListFields($tableName);
            foreach ($voList as $k => &$v){
                foreach ($v as $column => $value){
                    if(!in_array($column,$fields)) unset($v[$column]);
                }
            }
        }*/


        //2.当前表有关联的表字段时，取关联表信息并合并。实现join功能
        $this->cgf->mergeRelatedTableData($voList);

        //3.调用字段显示处理函数
        $this->cgf->executeColumnCallback($voList);


        //与后台管理的list里调用相关显示函数有重复，如|optionValue
        //if (method_exists ( $this, 'dateToViewModel' ))  $this->dateToViewModel ( $voList );


        //========================================== cgf  end =========================================


        //$voList = $voList->toArray();
        //echo $model->getlastsql();exit('x');
        //分页跳转的时候保证查询条件

        /* foreach ( $map as $key => $val ) {
             if (! is_array ( $val ) && !in_array($key,['_logic'])) {
                 //$p->parameter .= "$key=" . urlencode ( $val ) . "&";
                 if(strtolower(MODULE_NAME) == 'user' && $key =='uid'){
                     continue;
                 }
                 //$p->parameter[$key] =  urlencode ( $val );
             }
         }*/


        //先取分页数据防止_join处理数据，将分页数据丢弃
//            $this->assign('total', $voList->total());
//            $this->assign('per_page', $voList->listRows());
//            $this->assign('current_page', $voList->currentPage());
//            $this->assign('last_page', $voList->lastPage());
        //会将volist由对象转数组
        if (method_exists($this, '_join')) {
            $voList = $this->_join($voList);
        }

        //将导出excel功能注入到此处
        if ($this->request->action() == 'exportExcel') {
            $this->realExportExcel($voList->getCollection());
        }
        return $voList;
    }


    function assign($key, $value)
    {
        //模板赋值显示
        $this->pageVar[$key] = $value;
    }


    public function forbid()
    {
        return $this->disableField("status");
    }

    public function pass()
    {
        return $this->enableField("status");
    }

    public function recycle()
    {
        return $this->disableField("is_delete");
    }

    public function recycleBin()
    {
        $ids = $this->getIds();
        return $this->switchFieldState($ids, "is_delete", 1);
    }

    function resume()
    {
        $ids = $this->getIds();
        return $this->switchFieldState($ids, "status", 1);
    }


    function saveSort()
    {
        $seqNoList = $_POST ['seqNoList'];
        if (!empty ($seqNoList)) {
            //更新数据对象
            $name = CONTROLLER_NAME;
            //$model = D ($name);
            $col = explode(',', $seqNoList);
            //启动事务
            $this->m->startTrans();
            foreach ($col as $val) {
                $val = explode(':', $val);
                $this->m->id = $val [0];
                $this->m->sort = $val [1];
                $result = $this->m->save();
                if (!$result) {
                    break;
                }
            }
            //提交事务
            $this->m->commit();
            if ($result !== false) {
                //采用普通方式跳转刷新页面
                $this->success('更新成功');
            } else {
                $this->error($this->m->getError());
            }
        }
    }


    public function toview($data = "", $tpl = "", $msg = '成功')
    {
        if (empty($data)) $data = $this->pageVar;
        $jsonData = [];
        $jsonData['code'] = 0;
        $jsonData['msg'] = $msg;
        $jsonData['data'] = $data;
        return json($jsonData);
    }


    function jsonError($msg, $code = 0, $data = [])
    {
        $jsonData = [];
        $jsonData['code'] = $code;
        $jsonData['msg'] = $msg;
        $jsonData['data'] = $data;
        return json($jsonData);

    }

    function error($msg = '', $code = 1, $jumpUrl = '', $data = [])
    {

        $data = [];
        $data['code'] = $code;
        $data['msg'] = $msg;
        $data['data'] = (object)$data;
        return $this->JsonError($msg, $code, (object)array());

    }


    function exportExcel()
    {
        return $this->index();
    }

    function realExportExcel($voList)
    {
        header('Content-type: text/html; charset=utf-8');
//        var_dump($list->getCollection());exit;
        $xlsCell = $this->cgf->getNameAndZh();
        $xlsName = $this->cgf->definition->getTableDefinition()['title'];
        foreach ($voList as $k => $v) {
            //$xlsData[$k]['status'] = 1 ? '正常':'锁定';
            //$xlsData[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
        }
        exportExcel($xlsName, $xlsCell, $voList);
    }

    function _before_export()
    {
        C('URL_MODEL', 0); //解决时间搜索中 空格被转成+号，导致下一页内容无法显示
        if (empty($_REQUEST['listRows'])) $_REQUEST['listRows'] = 50;
    }

    function isUserModule()
    {
        return $this->request->module == "u";
    }

    function isAdminModule()
    {
        return $this->request->module == "admin";
    }
}
