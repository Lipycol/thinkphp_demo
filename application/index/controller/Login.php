<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\AdminInfo as AdminInfoModel;

class Login extends Controller {

  public function index() {
    return $this->fetch();
  }

  /**
     * 用户登录
     */
    public function login() {
      $param = request()->post();
      // if (!isset($param['captcha_token']) || isEmptyStr($param['captcha_token'])) {
      //     return re_json(400, '参数错误！');
      // }
      // $captcha = cache($param['captcha_token']);
      // if(!captcha_check($param['code']))
      //   {
      //     return re_json(400, '验证码错误，请重新输入！');
      //   }
      // if (!isset($param['account']) || isEmptyStr($param['account'], 100) ||!isset($param['password']) || isEmptyStr($param['password'], 100)) {
      //     return re_json(400, '账号或密码为空或格式不正确，请重新输入！');
      // }
      $result = $this->validate($param, 'app\index\validate\User');
      if (true !== $result) {
        return re_json(400, $result);
      }
      return (new AdminInfoModel())->checkLogin($param['account'], $param['password']);
  }

  public function test() {
    return captcha();
  }
  
  public function verify() {
    $code = request()->post('code');
    if(!captcha_check($code))
    {
      return '错误';
    } else {
      return '正确';
    }
  }

  public function hello($name = 'Luxuria')
    {
        return 'hello,' . $name;
    }
  public function check() {
    $param = request()->post();
    $username = $param['username'];
    $password = $_POST['password'];
    echo (request()->post() === $_POST);

    if ($username == "admin" && $password == "123") {
      $this->success('跳转成功', url('student/index/index'));
      // $this->redirect('student/index/index');
    } else {
      $this->error('登录失败');
    }
  }
  public function cdx() {
    $this->redirect('index/index', ['id'=>100, 'name'=>'abc']);
  }
  public function empty() {
    $this->redirect('index/index');
  }
}
