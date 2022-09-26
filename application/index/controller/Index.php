<?php
namespace app\index\controller;

use think\Controller;   

class Index extends Controller
{
    // protected $middleware = ['Check'];

    public function index()
    {
        return $this->fetch('test/menu');
        return $this->hello();
        // return redirect('api/controller/index');
        // $login = new \app\index\controller\Login;
        // return $login->index();
        return $this->request->root(true);
    }

    public function hello($name = 'ThinkPHP5.1')
    {
        return 'hello,' . $name;
    }
}
