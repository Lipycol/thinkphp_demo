<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function re_json($code=200, $msg='操作成功！', $data=[]) {
  return json(['code'=>$code, 'msg'=>$msg, 'data'=>$data]);
}

//缓存token
function cacheToken($user){
  $randChars = getRandChar(32);
  $timestamp = time();
  $token = md5($randChars . $timestamp);
  cache($token, $user, 864000);
  return $token;
}

//随机数
function getRandChar($length){
  $str = null;
  $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
  $max = strlen($strPol)-1;
  for($i=0;$i<$length;$i++){
      $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
  }
  return $str;
}

//判断字段为空或者是否超出长度
function isEmptyStr($str,$len=100){
  if (empty($str) || mb_strlen($str)>$len){
      return true;
  }
  return false;
}