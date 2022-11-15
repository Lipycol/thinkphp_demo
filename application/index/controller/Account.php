<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\AdminInfo as AdminInfoModel;

class Account extends Controller {

    protected function initialize() {
        parent::initialize();
    }

    protected $middleware = ['Check'];

    /**
     * 账号列表
     */
    public function accountList() {
        $param = request()->post();
        $page = (isset($param['page']) && is_numeric($param['page'])) ? $param['page']:  1;
        $limit = (isset($param['limit']) && is_numeric($param['limit'])) ? $param['limit'] : 10;
        $admin = cache(request()->header('token'));
        return (new AdminInfoModel())->infoPageList($admin['role_id'], $page, $limit);
    }

    /**
     * 退出登录
     */
    public function exitLogin() {
        $token = request()->header('token');
        if(empty($token)) {
            return re_json(400, '凭证为空！');
        }
        if(!cache($token)) {
            return re_json(400, '无效凭证！');
        }
        cache($token, null);
        return re_json(200, '退出成功!');
    }
    
    public function addBanner() {
        $param = request()->post();
        if (!isset($param['type']) || isEmptyStr($param['type']) || !in_array($param['type'], [1,2])) {
            return re_json(400, '活动类型格式不正确，请重新输入！');
        }
        if (!isset($param['pic']) || isEmptyStr($param['pic'], 255)) {
            return re_json(400, 'Banner图片为空或格式不正确，请重新上传！');
        }
        if (!isset($param['is_show']) || isEmptyStr($param['is_show'], 11) || !in_array($param['is_show'], [1,2])) {
            return re_json(400, '展示状态为空或格式不正确，请重新选择！');
        }
        if (!isset($param['jump_place']) || isEmptyStr($param['jump_place'], 11) || !in_array($param['jump_place'], [1,2,3,4])) {
            return re_json(400, '跳转位置为空或格式不正确，请重新选择！');
        }
        if (isset($param['jump_info']) && !empty($param['jump_info'] && isEmptyStr($param['jump_info'], 255))) {
            return re_json(400, '跳转链接格式不正确，请重新输入！');
        }
        if ($param['jump_place'] != 1) {
            if(!isset($param['jump_info']) || isEmptyStr($param['jump_info'])) {
                return re_json(400, '跳转链接为空或格式不正确，请重新输入！');
            }
            if ($param['jump_place'] == 2) {
                $info = db('product_info')->column('product_id');
                if (!in_array($param['jump_info'], $info)) {
                    return re_json(400, '所选择跳转商品链接不存在，请重新选择！');
                }
            }
            if ($param['jump_place'] == 3) {
                $info = db('activity')->column('activity_id');
                if (!in_array($param['jump_info'], $info)) {
                    return re_json(400, '所选择跳转活动链接不存在，请重新选择！');
                }
            }
        }
    }
}