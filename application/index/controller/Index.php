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

    public function test() {
        return 'hello,world!';
    }

    public function hello($name = 'ThinkPHP5.1')
    {
        return 'hello,' . $name;
    }

    function findPasswordKey($str1='',$str2=''){
        $temp = [];
        $max = 0;
        $index = null;
        for ($i = 0;$i<strlen($str1);$i++){
            $temp[$i] = [];
            for ($j = 0;$j<strlen($str2);$j++){
                if ($str1[$i] === $str2[$j]) {
                    if ($i > 0 && $j > 0 && $temp[$i - 1][$j - 1] > 0) {
                        $temp[$i][$j] = 1 + $temp[$i - 1][$j - 1];
                    } else {
                        $temp[$i][$j] = 1;
                    }
                    //保存当前temp中最大的数字，并
                    if ($max < $temp[$i][$j]) {
                        $max = $temp[$i][$j];
                        $index = $i;
                    }
                } else {
                    $temp[$i][$j] = 0;
                }
            }
        }
        return strlen(substr($str1,$index - $max + 1, $max));
    }

    function findPasswordKey2($str1='',$str2=''){
        $temp = 0;
        $max = 0;
        for ($i = 0;$i<strlen($str1);$i++){
            for ($j = 0;$j<strlen($str2);$j++){
                if ($str1[$i] === $str2[$j]) {
                    if ($i > 0 && $j > 0 && $str1[$i-1] === $str2[$j-1]) {
                        $temp++;
                    } else {
                        $temp = 1;
                    }
                    $max = ($max < $temp) ? $temp : $max;
                }
            }
        }
        return $max;
    }

    function getAddressInfo($ip){
        try {
            $ch = curl_init();
            $url = 'https://whois.pconline.com.cn/ipJson.jsp?ip=' . $ip;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $data = curl_exec($ch);
            curl_close($ch);
            $data = mb_convert_encoding($data, 'utf-8', 'GB2312'); // 转换编码
            // 截取{}中的字符串
            $data = substr($data, strlen('({') + strpos($data, '({'), (strlen($data) - strpos($data, '})')) * (-1));
            // 将截取的字符串$data中的‘，’替换成‘&’   将字符串中的‘：‘替换成‘=’
            $data = str_replace('"', "", str_replace(":", "=", str_replace(",", "&", $data)));
            parse_str($data, $addressInfo); // 将字符串转换成数组格式
    //    return $addressInfo['addr']; // 返回ip归属地
            return $addressInfo['pro'].$addressInfo['city']; // 返回ip归属地
        }catch (\Exception $e){
            return false;
        }
    
    }
}
