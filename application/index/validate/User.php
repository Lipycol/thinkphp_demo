<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate {
    protected $rule = [
        'account' => 'require|max:100',
        'password' => 'length:9,100'
    ];

    protected $message = [
        'account.require' => '账号不能为空',
        'account.max' => '账号格式不正确',
        'password.require' => '密码不能为空',
        'password.length' => '密码格式不正确'
    ];
}