<?php
namespace app\index\controller;

use think\Controller;

class Login extends Controller {

  protected $middleware = ['Check'];

  public function index() {
    return $this->fetch();
  }

  public function test() {
    return 'abc';
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
