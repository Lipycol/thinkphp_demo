<?php
namespace app\index\model;

use think\Db;
use think\Model;
use think\Exception;

class AdminInfo extends Model {

    /**
     * 账号列表
     */
    public function infoPageList($role_id, $page, $limit) {
        return cache(request()->header('token'));
        if ($role_id != 0) {
            $data = $this->alias('a')
                ->where('admin_id', $this->admin['admin_id'])
                ->field("admin_id, account, admin_name, email, a.role_id, (case a.role_id when '0' then '超级管理员' else role_name end) as role_name, state")
                ->leftJoin('role_info r', 'r.role_id = a.role_id')
                ->find();
            return re_json(200, '', $data);
        }
        if ($page == 0) {
            $data = $this->alias('a')
                ->field("admin_id, account, admin_name, email, a.role_id, (case a.role_id when '0' then '超级管理员' else role_name end) as role_name, state")
                ->leftJoin('role_info r', 'r.role_id = a.role_id')
                ->order('admin_id')
                ->select()
                ->toArray();
            return re_json(200, '', $data);
        } else {
            $data = $this->alias('a')
                ->field("admin_id, account, admin_name, email, a.role_id, (case a.role_id when '0' then '超级管理员' else role_name end) as role_name, state")
                ->leftJoin('role_info r', 'r.role_id = a.role_id')
                ->page($page, $limit)
                ->order('admin_id')
                ->select()
                ->toArray();
            $count = $this->count();
            return re_json(200, '', ['list' => $data, 'count' => $count, 'page' => $page, 'limit' => $limit, 'allPage' => ceil($count / $limit)]);
        }
    }

    /**
     * 检查登录
     */
    public function checkLogin($account, $password) {
        try {
            // 判断账号
            $data = $this->where('account', $account)->find();
            if (!isset($data) || empty($data)) {
                return re_json(400, '账号或密码错误，请重新输入！');
            }
            if (!password_verify($password,$data['password'])) {
                return re_json(400, '账号或密码错误，请重新输入！');
            }
            if ($data['state'] != 1) {
                return re_json(400, '账号已被禁用，请联系管理员！');
            }
            if ($data['role_id'] == 0) {
                $role['role_name'] = '超级管理员';
            } else {
                $role = db('role_info')->where('role_id', $data['role_id'])->find();
            }
        } catch (Exception $e) {
            return re_json(500, '系统内部错误！');
        }
        $token = cacheToken($data);
        return re_json(200, '登录成功！', ['name' => $data['admin_name'], 'role_id' => $data['role_id'], 'token' => $token]);
    }

}