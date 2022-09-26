<?php
namespace app\student\model;

use think\Db;
use think\Model;

class Student extends Model {
  public function findData() {
    $data = $this->alias('stu')
      ->join('college col', 'col.id=stu.college_id')
      ->field('stu.*, col.name as college_name')
      ->order('id', 'asc')
      ->select();
    return $data;
  }

  public function collegeGroup() {
    $data = $this->alias('stu')
      ->join('college col', 'col.id = stu.college_id')
      ->field('stu.college_id, col.name as college_name, count(stu.id) as stu_count')
      ->group('stu.college_id')
      ->select();
    return $data;
  }

  public function addData($param) {
    try {
      Db::startTrans();
      $add['id'] = $param['uid'];
      $add['name'] = $param['uname'];
      $this->insert($add);
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return re_json(400, '操作失败，已存在此数据！');
    }
    return re_json(200, '操作成功！');
  }

  public function updateData($param) {
    try {
      Db::startTrans();
      $check = $this->where('id', $param['uid'])
        ->findOrFail();
      $this->where('id', $param['uid'])
        ->update(['name' => $param['uname']]);
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return re_json(400, '操作失败，不存在此数据！');
    }
    return re_json(200, '操作成功！');
  }

  public function deleteData($param) {
    try {
      Db::startTrans();
      $check = $this->where('id', $param)
        ->findOrFail();
      $this->where('id', $param)
        ->delete();
      Db::commit();
    } catch (\Exception $e) {
      return re_json(400, '操作失败，不存在此数据！');
    }
    return re_json(200, '操作成功！');
  }
}