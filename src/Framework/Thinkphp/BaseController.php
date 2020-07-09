<?php

declare (strict_types=1);

namespace madmin\controller;

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
use liliuwei\think\Jump;


/**
 * 控制器基础类
 */
abstract class BaseController
{

    use \liliuwei\think\Jump;

    public $u_id;
    public $store_id;
    public $user;
    public $cgf; //静态类会不会更好？

    /** @var  Model */
    public $m;
    public $moduleName;
    public $controllerName;
    public $actionName;
    public $pageVar = [];
    public $autoInstantiateModel = true;
    public $allowUpdateFields = [];
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
    public $front_user_id = 'uid';

    /**
     * 后台用户id标识
     * @var
     */
    public $backend_user_id = 'admin_id';


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

    /**
     * 构造方法
     * @access public
     * @param  App $app 应用对象
     */
    public function __construct(App $app)
    {


        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }


    /**
     * 验证数据
     * @access protected
     * @param  array $data 数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array $message 提示信息
     * @param  bool $batch 是否批量验证
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
            $v     = new $class();
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
        $stub      = $this->modelTemplate;
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

    function createModelAutomatically($modelName)
    {
        $className = '\\app\\model\\' . $modelName;
        if (!class_exists($className)) {
            //$this->makeModelFile('app\\model\\'.$modelName);//exit;
            Console::call('make:model', ['app\\model\\' . $modelName]);
        }
        $this->m = app($className);
        //$this->m = new $className();

    }


    // 初始化
    protected function initialize()
    {
        $route       = $this->app->request->rule()->getRoute();
        $vendorRoute = substr($route, strrpos($route, '\\') + 1);
        $route       = str_replace('@', '/', $vendorRoute);
        $arrRoute = explode('/',$route);
        $controllerName = $arrRoute[0];
        $actionName = $arrRoute[1];

        //var_dump(Request::isAjax());exit;
        define('CONTROLLER_NAME',$controllerName);
        define('IS_AJAX', Request::isAjax());
        define('IS_GET', Request::isGet());
        define('IS_POST', Request::isPost());
        define('URL_IMG', '/runtime/storage');
        //var_dump(CONTROLLER_NAME);exit;

        $this->moduleName     = app('http')->getName();
        $this->moduleName     = 'index';
        $this->controllerName = $controllerName;
        $this->actionName     = $actionName;
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
            $appBasePath = __DIR__;
            $dbconfig    = include('../config/database.php');
            $dbconfig    = $dbconfig['connections']['mysql'];
            //dump(Cgf::$config);exit;
            $dbconfig = Cgf::getDbConfigFromThinkPHP($dbconfig); //将tp6 db配置转成cgf的配置

            $cgfConf                   = [];
            $cgfConf['dbConfig']       = $dbconfig;
            $cgfConf['savePath']       = $appBasePath . "/cgf/definition";//保存cgf生成的定义文件
            $cgfConf['framework']      = 'thinkphp';//使用的框架
            $cgfConf['validate']       = 'thinkphp';//使用验证库
            $cgfConf['form']           = 'bootstrap';//表单使用的框架
            $cgfConf['currentName']    = 'common';//当前模块名
            $cgfConf['tableName']      = $tableName;//表名
            $cgfConf['controllerName'] = $this->controllerName;//控制器名
            $cgfConf['appRootPath']    = $appBasePath;//框架应用程序根目录

            $viewDir                         = $this->app->getAppPath() . "view/" . $this->request->module . "/";
            $cgfConf['parentTemplatePath']   = $viewDir . 'public/';//cgf生成模板使用的父模板,cgf会根据这里的模板来生成应用模板
            $cgfConf['templateSavePath']     = $viewDir . "{$tableName}";//cgf生成的模板保存路径
            $cgfConf['availableModule']      = ['common', 'admin', 'index'];//可用模块
            $cgfConf['autoHiddenPrimaryKey'] = false;//是否将主键表单类型设为hidden

            $this->cgf = new Cgf($cgfConf);
        }
        if (empty($this->allowUpdateFields) && !empty(config('app.allowUpdateFields')[$this->controllerName])) {
            $this->allowUpdateFields = config('app.allowUpdateFields')[$this->controllerName];
        }
    }

    public function index()
    {
        $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($this->m);
        }
        if (!empty ($this->m)) {
            $this->_list($this->m);
        }

        $default_return_format = config('app.default_return_format', 'html');
        if (in_array($this->request->module, ["admin", "uer"]) && $default_return_format == 'html') { //only backend need generate template
            $r = $this->cgf->generateListsTemplate();//生成模板
        }
        return $this->toview();
    }


    function edit()
    {

        //自动获取添加模板
        $tpl = $this->cgf->generateAddTemplatel(true);

        $this->generateOptions();

        $id          = input($this->m->getPk());
        $where       = [];
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
        $this->cgf       = "cgf";

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
        if ($this->uid) {
            $data['uid']      = $this->uid;
            $data['store_id'] = $this->store_id;
        }

        $pk = $this->m->getPk();
        $id = $data[$pk];

        //验证编辑保存权限
        if (!empty($id)) {
            $rModel = $this->m->where([$this->m->getPk() => $id, "store_id" => $this->store_id])->find();
            if (empty($rModel)) return $this->error('没有所有者权限');
        }

        //处理上传
        if (haveUploadFile()) {
            $uploadInfo = $this->commonUpload();
            if (!empty($uploadInfo)) {
                $data = array_merge($data, $uploadInfo);
            }
        }

        //字段验证
        //validate('app\validate\User')->check($data);
        /* $m = new \app\model\User();
         $className = '\\app\\model\\User';
         $m = new $className();
         dump($m);*/

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

        $this->success();
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
        $id = input('id');
        if (empty($id)) {
            return "参数id不能为空";
        }
        $vo = $this->m->find($id)->toArray();
        if (empty($vo)) return $this->error('数据不存在');
        if (method_exists($this, '_show')) {
            $this->_show($vo);
        }
        $this->assign('vo', $vo);
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
        if (empty($this->uid)) return $this->error('未登录');
        if (!in_array($filed, $this->allowUpdateFields)) return $this->error('非法类型操作');
        $r = $this->m->where(['id' => $ids, 'store_id' => $this->store_id])->update([$filed => $value]);
        if (empty($r)) return $this->error('更新失败');
        return $this->toview();
    }

    function upload($stype = 'file')
    {
        $uploadInfo   = $this->commonUpload($stype);
        $this->upload = $uploadInfo;
        return $this->toview();
    }

    function commonUpload($moduleDir = 'file')
    {
        return commonUpload($moduleDir);
    }

    public function delete()
    {
        $pk = $this->m->getPk();
        $id = input($pk);
        $id = (string)$id;
        if (empty ($id)) return $this->error('非法操作');
        $condition = array($pk => explode(',', $id), "store_id" => $this->store_id);

        //验证编辑保存权限
        if (!empty($id)) { //编辑保存验证
            $rModel = $this->m->where($condition)->find();
            if (empty($rModel)) {
                return $this->error('没有所有者权限');
            }
        }

        $rDelete = $this->m->where($condition)->delete();
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
        $rDelete   = $this->m->where($condition)->update(['status' => 0]);
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
        if (IS_AJAX) {
            C('ret_format', 'json');
        } elseif (!empty(input('callback'))) {
            C('ret_format', 'jsonp');
        }

        if ($this->enableLog && !IS_CLI) $this->requestLog();

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


        $referer = empty($_SERVER['HTTP_REFERER']) ? '/' : $_SERVER['HTTP_REFERER'];
        C('referer', $referer);
        $this->platform = $this->getPlatform();
        //$this->referer = $_SERVER['HTTP_REFERER'];


        /*//url带openid 自动写cookie,session等登录标识
        $open_id = input('open_id');
        if($open_id){
            $r =  M('User')->where(["open_id" => $open_id,"type" => input('type')])->find();
            if(!empty($r)){
                $r['uid'] = $r['id'];
                $this->tempStorageOpenidUser = $r;
                setUserAuth($r); //登录前在getAuth 里增加个标识，如果get open_id有值，不必取cookie,直接标识为登录
            }
        }


        //exit('x');exit;
        //用户信息
        if(!empty($this->uid)){
            $m = M('User');
            $r = $m->find($this->uid);
            //var_dump($r);
            //echo $m->getLastSql();
            //exit;
            if(empty($r['nickname']) && !empty($r['username'])) $r['nickname'] = $r['username'];
            $this->assign ( 'user', $r );
        }

        if(C('USER_AUTH_ON')){
            import ( '@.ORG.Util.RBAC_WEB' );
            $app = 'USER';

        }else{
            import ( 'ORG.Util.RBAC' );
            $app = APP_NAME;
        }*/
    }

    function getPlatform()
    {
        $platform = input('server.platform');
        if (empty($platform)) {
            if (IS_MOBILE) {
                if (is_weixin()) {
                    $platform = 'wx';
                } else {
                    $platform = 'wap';
                }
            } else {
                $platform = 'pc';
            }
        }
        return $platform;
    }

    /**
     * 设置用户id
     * @param $user_id
     */
    protected function setUserId($user_id)
    {
        $_REQUEST['uid'] = $_POST['uid'] = $_GET['uid'] = $user_id;//$this->uid;
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
        $this->uid  = $dbUserInfo['id'];
        $this->user = $dbUserInfo;
        $this->assign('uid', $this->uid);
        $this->assign('user', $this->user);
        return $dbUserInfo;
    }


    /**
     * 访问日志，记录用户请求的参数
     */
    function requestLog()
    {


        $data        = array();
        $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (IS_POST) {
            $params = $_POST;
        } elseif (IS_GET) {
            $params = $_GET;
        }
        if (empty($params)) $params['input'] = file_get_contents("php://input");
        $data['params'] = json_encode($params);
        //$data['cookie'] = json_encode($_COOKIE);
        //$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['ip']        = get_client_ip();
        $detail            = array();
        $detail['request'] = $_REQUEST;

        $header = [];
        $fields = ['HTTP_USER_ID', 'HTTP_DEVICE_VID', 'HTTP_DEVICE_ID', 'HTTP_PLATFORM', 'HTTP_VERSION']; //'HTTP_USER_AGENT',
        foreach ($fields as $k => $v) {
            if (empty($_SERVER[$v])) continue;
            $header[$v] = $_SERVER[$v];
        }
        /*$this->version = input('server.HTTP_VERSION');
        $this->device_id = input('device_id') ?:input('server.HTTP_DEVICE_ID');
        $this->platform = input('server.HTTP_PLATFORM');
        $user_id = input('uid') ?: input('server.HTTP_USER_ID');
        $detail['server'] = $_SERVER;*/
        //$detail['header'] = $header;
        //$data['detail'] = json_encode($detail);
        $url     = $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . " " . $_SERVER['SERVER_PROTOCOL'] . "\r\n";
        $request = $url . getallheaders(true);

        $raw_post = '';
        if (IS_POST) {
            $raw_post = http_build_query($_POST);
            if (empty($raw_post)) {
                $raw_post = file_get_contents("php://input");
            }
        }
        $request .= "\r\n" . $raw_post;

        $data['detail']      = $request;
        $data['user_agent']  = $_SERVER['HTTP_USER_AGENT'];
        $data['platform']    = input('server.HTTP_PLATFORM');
        $data['uid']         = cookie('uid');//cookie可能取出null,要求字段必须可为null
        $data['create_time'] = date("Y-m-d H:i:s");
        $data['method']      = $_SERVER['REQUEST_METHOD'];
        $data['date_int']    = time();


        try {
            $m = M('LogRequest', '', C('log_db'));
            //$m->create($data);
            $logId = $m->add($data);
            C('logId', $logId);
        } catch (\Exception $e) {
            tplog($e->getMessage());
        }


        //echo $m->getLastSql();exit;

    }

    /**
     * 记录响应，调用的地方有:\Think\Control->ajaxReturn()
     * @param $id
     * @param $response
     */
    function responseLog($id, $response)
    {
        /* $data = [];
         $data['id'] = $id;
         $data['response'] = $response;
         $m = M('LogRequest','',C('log_db'));
         $m->save($data);*/

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
        $map            = [];
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

    protected function _search()
    {
        $requestParam = input();
        //$requestParam['uid'] = $this->request->uid;
        $front_user_id                  = $this->front_user_id;
        $backend_user_id                = $this->backend_user_id;
        $requestParam[$front_user_id]   = $this->request->$front_user_id;
        $requestParam[$backend_user_id] = $this->request->$backend_user_id;
        $requestParam['store_id']       = $this->request->store_id;

        $requestParam['status'] = $this->request->status;
        //var_dump($requestParam);exit;
        $autoIndistinct = true;
        $tableName      = Str::snake($this->controllerName, '_');
        //var_dump($this->m->getFields());exit;
        $fields = Db::name($tableName)->getFields(); //包含字段类型，注释等
        foreach ($fields as $column => $definition) {
            $inputValue = $requestParam[$column];
            if ($inputValue !== null && $inputValue != '') {
                if ($autoIndistinct && $this->columnType($definition['type']) == 'string') {
                    $this->m = $this->m->where($column, 'like', '%' . $inputValue . '%');
                } else {
                    $this->m = $this->m->where($column, '=', $inputValue);
                }
            }
        }
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
    protected function _list($model, $sortBy = '', $asc = false)
    {

        //排序字段 默认为主键名
        if (!empty($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = !empty ($sortBy) ? $sortBy : $model->getPk();
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        //$setOrder = setOrder(array(array('viewCount', 'a.view_count'), 'a.id'), $orderBy, $orderType, 'a');
        if (!empty($_REQUEST ['_sort'])) {
            $sort = $_REQUEST ['_sort'];
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        $sort = 'asc';

        //取得满足条件的记录数

        $count = $model->count();
        if ($count > 0) {
            //import ( "ORG.Util.Page" );
            //创建分页对象
            if (!empty ($_REQUEST ['_listRows'])) {
                $listRows = $_REQUEST ['_listRows'];
            } elseif (!empty($this->listRows)) {
                $listRows = $this->listRows;
            } else {
                $listRows = '20';
            }

            if ($this->request->module == 'user') {
                unset($_GET['uid']);
            }
            //========================================== cgf  start =========================================

            //1.生成查询字段
            $selectFields = $this->cgf->generateListSelectColumn();
            //var_dump($selectFields);exit;
            //if(strpos($order,'`') === false) $order = "`" . $order . "` ";
            //$r = $model->select();
            //var_dump($r);exit;

            //->field($selectFields)

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
            $voList = $model->order($order . ' ' . $sort)->paginate($listRows, false, ['query' => []]);

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


            if (method_exists($this, '_join')) $this->_join($voList);

            //将导出excel功能注入到此处
            if ($this->request->action() == 'exportExcel') {
                $this->realExportExcel($voList);
            }

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


            //列表排序相关
            $sortImg = $sort == 'desc' ? "glyphicon-arrow-down" : "glyphicon-arrow-up"; //排序图标 glyphicon glyphicon-arrow-up
            if ($sort == 'desc') {
                $sortImg = 'glyphicon-arrow-down';
            } elseif ($sort = 'asc') {
                $sortImg = 'glyphicon-arrow-up';
            } else {
                $sortImg = 'glyphicon-sort';
            }

            $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
            $sort    = $sort == 'desc' ? 'asc' : 'desc'; //页面上显示的下一次排序方式
            $this->assign('sort', $sort);
            $this->assign('sortAlt', $sortAlt);
            $this->assign('sortImg', $sortImg);
            $this->assign('order', $order);

            //$this->pageVar = $voList;
            /*$voList = [];
            $voList[] = ['id'=>1,'name'=>'a'];
            $voList[] = ['id'=>2,'name'=>'b'];*/

            // 获取分页显示
            $page = $voList->render();
            $this->assign('page', $page);
            $this->assign('list', $voList);


            /*$this->assign('total',$voList['total']);
            $this->assign('per_page',$voList->per_page);
            $this->assign('current_page',$voList->current_page);
            $this->assign('last_page',$voList->last_page);
            $this->assign('data',$voList->data);*/


            //html分页
            //$page = $voList->render();


            //顶部简易分页
            if ($this->enableLitePage) {
                $nextIndex = $p->nowPage + 1;
                if ($nextIndex > $p->totalPages) $nextIndex = $p->totalPages;
                $nextPageUrl = $p->url($nextIndex);
                $prevIndex   = $p->nowPage - 1;
                if ($prevIndex < 1) $prevIndex = 1;
                $prevPageUrl = $p->url($prevIndex);
                $this->assign('nextPageUrl', $nextPageUrl);
                $this->assign('prevPageUrl', $prevPageUrl);
            }
        } else {
            $this->assign('data', []);
        }
        //cookie( '_currentUrl_', __SELF__ );
        return;
    }

    function assign($key, $value)
    {

        //模板赋值显示
        $this->pageVar[$key] = $value;

        //View::assign($key,$value);
    }


    public function clear()
    {
        //删除指定记录
        $name = CONTROLLER_NAME;
        //$this->m = D ($name);
        if (!empty ($this->m)) {
            if (false !== $this->m->where('status=1')->delete()) {
                $this->assign("jumpUrl", $this->getReturnUrl());
                $this->success(L('_DELETE_SUCCESS_'));
            } else {
                $this->error(L('_DELETE_FAIL_'));
            }
        }
        $this->forward();
    }

    public function forbid()
    {
        $name = CONTROLLER_NAME;
        //$model = D ($name);
        $pk        = $this->m->getPk();
        $id        = $_REQUEST [$pk];
        $condition = array($pk => array('in', $id));
        $list      = $this->m->forbid($condition);
        if ($list !== false) {
            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('状态禁用成功');
        } else {
            $this->error('状态禁用失败！');
        }
    }

    public function checkPass()
    {
        $name = CONTROLLER_NAME;
        //$model = D ($name);
        $pk        = $this->m->getPk();
        $id        = $_GET [$pk];
        $condition = array($pk => array('in', $id));
        if (false !== $this->m->checkPass($condition)) {
            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('状态批准成功！');
        } else {
            $this->error('状态批准失败！');
        }
    }

    public function recycle()
    {
        $name = CONTROLLER_NAME;
        //$model = D ($name);
        $pk        = $this->m->getPk();
        $id        = $_GET [$pk];
        $condition = array($pk => array('in', $id));
        if (false !== $this->m->recycle($condition)) {

            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('状态还原成功！');

        } else {
            $this->error('状态还原失败！');
        }
    }

    public function recycleBin()
    {
        $map            = $this->_search();
        $map ['status'] = -1;
        $name           = CONTROLLER_NAME;
        //$model = D ($name);
        if (!empty ($this->m)) {
            $this->_list($this->m, $map);
        }
        $this->display();
    }

    function resume()
    {
        $name = CONTROLLER_NAME;
        //$model = D ($name);
        $pk        = $this->m->getPk();
        $id        = $_GET [$pk];
        $condition = array($pk => array('in', $id));
        if (false !== $this->m->resume($condition)) {
            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('状态恢复成功！');
        } else {
            $this->error('状态恢复失败！');
        }
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
                $val           = explode(':', $val);
                $this->m->id   = $val [0];
                $this->m->sort = $val [1];
                $result        = $this->m->save();
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
        $jsonData              = [];
        $jsonData['code']      = 1;
        $jsonData['msg']       = $msg;
        $returnFormat          = 'json';
        $default_return_format = config('app.default_return_format', 'html');
        $isAjax                = $this->request->isAjax();
        if ($isAjax || input('ret_format') == "json") {
            $default_return_format = 'json';
        }
        //$default_return_format = $this->request->$default_return_format;
        if ($default_return_format == 'json') {
            if (!empty($data['list'])) {
                $jsonData['data'] = $data['list'];
            } else {
                $jsonData['data'] = $data;
            }

            return json($jsonData);
        } elseif ($default_return_format == 'jsonp') {
            $jsonData['data'] = $data;
            return jsonp($jsonData);
        } elseif ($default_return_format == 'wap') {

            return view('', $data);
        } else {
            //$viewDir = $this->app->getAppPath() . "view/" . $this->request->module . "/";
            $controllerName = strtolower($this->controllerName);
            $viewDir = dirname(__DIR__).'/view/';//."/$controllerName/";
            $tpl = $this->controllerName."/".$this->actionName;
            View::config(['view_path' => $viewDir]);
            return view($tpl, $data);
        }
    }


    function JsonError($msg, $code = 0, $data = [])
    {
        $jsonData         = [];
        $jsonData['code'] = $code;
        $jsonData['msg']  = $msg;

        $returnFormat = 'json';
        if ($returnFormat == 'json') {
            $jsonData['data'] = $data;
            return json($jsonData);
        } elseif ($returnFormat == 'jsonp') {
            $jsonData['data'] = $data;
            return jsonp($jsonData);
        }
    }

    function error($msg = '', $code = 0, $jumpUrl = '', $data = [])
    {
        //var_dump($message);exit;
        //$ret_format = $this->responseFormat();
        $default_return_format = config('app.default_return_format');
        if (in_array($default_return_format, ['json', 'jsonp'])) {
            $data         = [];
            $data['code'] = $code;
            $data['msg']  = $msg;
            $data['data'] = (object)$data;
            if ($jumpUrl) $data['jumpUrl'] = $jumpUrl;

            return $this->JsonError($msg, $code, (object)array());
        }
        return ['error function'];
    }

    //用户信息
    function userinfo()
    {
        if (empty($this->uid)) return;

        $u        = M('OutletUser');
        $userinfo = $u->find($this->uid);
        unset($userinfo['id']);
        unset($userinfo['pwd']);
        unset($userinfo['open_id']);
        unset($userinfo['bind']);
        $userinfo       = json_encode($userinfo);
        $this->userinfo = $userinfo;

    }

    //设置标题
    function setTitle($title)
    {
        $this->pageTitle = empty($title) ? C('SITE_TITLE') : $title . '_' . C('SITE_TITLE');
        //$title && $title = $title."_";
        //$this->pageTitle = $title.C('SITE_TITLE');
    }

    //验证码
    public function createVerifyCode()
    {
        $Verify = new \Think\Verify();
        $Verify->entry();
    }

    function setParam($key, $value)
    {
        $_REQUEST[$key] = $_POST[$key] = $_GET[$key] = $value;
    }

    function exportExcel()
    {
        return $this->index();
    }

    function realExportExcel($list)
    {
        header('Content-type: text/html; charset=utf-8');

        $xlsCell = $this->cgf->getNameAndZh();
        $xlsName = $this->cgf->definition->getTableDefinition()['title'];
        foreach ($list as $k => $v) {
            //$xlsData[$k]['status'] = 1 ? '正常':'锁定';
            //$xlsData[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
        }
        exportExcel($xlsName, $xlsCell, $list);
    }

    function _before_export()
    {
        C('URL_MODEL', 0); //解决时间搜索中 空格被转成+号，导致下一页内容无法显示
        if (empty($_REQUEST['listRows'])) $_REQUEST['listRows'] = 50;
    }

}
