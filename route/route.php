<?php
use think\Request;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

// Route::get('hello/:name', 'index/hello');

Route::get('/','index');

Route::rule('stu/select', '@student/index/select')->name('stu_select');

Route::rule('hello/:name', function(Request $request, $name) {
    $method = $request->method();
    return '['.$method.'] Hello,'.$name;
});

return [

];
