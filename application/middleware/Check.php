<?php

namespace app\middleware;

use think\exception\HttpResponseException;

class Check
{
    protected $admin_token;
    protected $admin;
    public function handle($request, \Closure $next) {
        $controller = $request->controller();
        $action = $request->action();
        $mark = strtolower($controller).'-'.$action;
        //所有人都有的权限
        dump($action);
        $all_has_auth = ['role-authlist','role-allrole','product-classifylist',
            'product-getlevelclassify'];
        //不需要token的方法
        $no_token = [];
        if (in_array($mark,$no_token)){
            return $next($request); 
        }
        $admin_token = $request->header('token');
        $this->admin_token = $admin_token;
        $this->admin = cache($admin_token);
        //校验登录状态，1判断token2判断账号是否被禁用
        $this->isLogin();
        if (!in_array($mark,$all_has_auth)){
            //3判断是否有权限
            $this->checkAuth($mark);
        }
        return $next($request);
    }

    /*
     * 验证登录状态：是否存在账号或已被禁用
     * */
    protected function isLogin()
    {
        if (empty($this->admin_token) || empty($this->admin)){
            $json = re_json(401,'登陆过期,请重新登陆!');
            throw new HttpResponseException($json);
        }else{
            $user = db('admin_info')->where('admin_id',$this->admin['admin_id'])
                ->where('state',1)->count();
            if ($user==0){
                $json = re_json(401,'暂无账号信息或已被禁用!');
                throw new HttpResponseException($json);
            }
        }
        return true;
    }

    /*
     * 判断当前账号所属角色是否有权限操作
     * */
    protected function checkAuth($mark){
        //超级管理员拥有所有权限
        if ($this->admin['role_id'] == 0){
            return true;
        }
        $auth_id = db('auth_info')->where('auth_mark','=',$mark)->value('auth_id');
        if ($auth_id){
            //获取用户角色所拥有的权限
            $my_role = db('admin_info')->where('admin_id','=',$this->admin['admin_id'])->value('role_id');
            $my_auth_ids = db('role_info')->where('role_id','=',$my_role)->value('right_ids');
            $my_auth_ids = json_decode($my_auth_ids,true);
            if (!$my_auth_ids || !in_array($auth_id,$my_auth_ids)){
                $json = re_json(400,'权限不足');
                throw new HttpResponseException($json);
            }
        }else{
            $json = re_json(400,'权限未添加');
            throw new HttpResponseException($json);
        }
        return true;
    }


}
