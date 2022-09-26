<?php
namespace app\student\controller;

use app\student\model\Student as StudentModel;
use app\student\model\College as CollegeModel;
use think\Controller;
use think\facade\Request;

class Index extends Controller {

  public function index($name = 'luxuria') {
    echo '早上好，'.$name;
    echo '<br>';
    echo Request::ext();
    echo '<br>';
    echo Request::route('name', 'default');
    return $this->fetch();
  }
  public function select() {
    // $ad = ['Id'=>19, 'name'=>"ouyangaaa"];
    // $add = Db::table("student")->insert($ad);
    // $add = db("student")->insert($ad);
    // $this->success('跳转成功', url('student/index'));
    $data = (new StudentModel())->findData();
    if ($data) {
      // return json($data);
      return re_json(200, '操作成功！', $data);
    }
    return re_json(400, '操作失败！');
    // $add['Id'] = 5;
    // $add['name'] = "ouyang";
    // $ad = db('student')->insert($add);  
    // $data = db('student')->select();
    // dump($data);
  }
  public function add() {
    $uid = $_POST['uid'];
    $uname = $_POST['uname'];
    if ($uid && $uname) {
      $param['uid'] = $uid;
      $param['uname'] = $uname;
      if ((new StudentModel)->addData($param)) {
        // $this->success('数据插入成功', url('student/index/index'));
        return re_json(200, '操作成功！');
      }
      // $this->error('数据插入失败,已存在此数据');
      return re_json(400, '操作失败，已存在此数据！');
    }
    // $this->error('数据插入失败,缺少所需信息');
    return re_json(400, '操作失败，缺少所需信息！');
  }

  public function update() {
    $uid = $_POST['uid'];
    $uname = $_POST['uname'];
    if ($uid && $uname) {
      $param['uid'] = $uid;
      $param['uname'] = $uname;
      if ((new StudentModel())->updateData($param)) {
        // $this->success('数据更新成功', url('student/index/index'));
        return re_json(200, '操作成功！');
      }
      // $this->error('数据更新失败,不存在此数据');
      return re_json(400, '操作失败，不存在此数据！');
    }
    // $this->error('数据更新失败,缺少所需信息');
    return re_json(400, '操作失败，缺少所需信息！');
  }

  public function delete() {
    $uid = $_POST['uid'];
    if ($uid) {
      if ((new StudentModel())->deleteData($uid)) {
        // $this->success('数据删除成功', url('student/index/index'));
        return re_json(200, '操作成功！');
      }
      // $this->error('数据删除失败,不存在此数据');
      return re_json(400, '操作失败，不存在此数据！');
    }
    // $this->error('数据删除失败,缺少所需信息');
    return re_json(400, '操作失败，缺少所需信息！');
  }
}