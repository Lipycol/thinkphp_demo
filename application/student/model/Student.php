<?php
namespace app\student\model;
use think\Db;
use think\Model;

class Student extends Model {
  public function findData() {
    $data = $this->order('id', 'asc')->selectOrFail();
    return $data;
  }

  public function collegeGroup() {
    $data = $this->alias('stu')->join('college col', 'col.id = stu.college_id')->field('stu.college_id, col.name as college_name, count(stu.id) as stu_count')->group('stu.college_id')->selectOrFail();
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
      return false;
    }
    return true;
  }

  public function updateData($param) {
    try {
      Db::startTrans();
      $check = $this->where('id', $param['uid'])->findOrFail();
      $this
      ->where('id', $param['uid'])
      ->update(['name' => $param['uname']]);
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return false;
    }
    return true;
  }

  public function deleteData($param) {
    try {
      Db::startTrans();
      $check = $this->where('id', $param)->findOrFail();
      $this
      ->where('id', $param)
      ->delete();
      Db::commit();
    } catch (\Exception $e) {
      return false;
    }
    return true;
  }
}