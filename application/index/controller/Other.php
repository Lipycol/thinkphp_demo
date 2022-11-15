<?php
namespace app\index\controller;

use think\Controller;
use think\captcha\Captcha;

class Other extends Controller {

    public function getCaptcha() {
        $config = [
          // 验证码位数
          'length' => 4,
          // 验证码字体大小
          'fontSize' => 40,
          // 是否画混淆曲线
          'useCurve' => true,
          // 是否添加杂点
          'userNoise' => true,
          // 验证码字符集
          'codeSet' => '1234567890',
      ];
        $captcha = new Captcha($config);
        return $captcha->entry();
      }
}