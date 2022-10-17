<?php
namespace app\student\model;

use think\Model;
use think\Db;

class College extends Model {
  public function collegeStudent() {
    $data = $this->with(['student'=>function($e) {
      $e->field('id as stu_id, name as stu_name, college_id');
      }])->field('id as col_id, name as col_name')
      ->select();
    // $data = $this->alias('col')
    //   ->join('student stu', 'stu.college_id = col.id')
    //   ->field('col.id, col.name, stu.name')
    //   ->select();
    return $data;
  }

  public function student() {
    return $this->hasMany('student', 'college_id', 'col_id');
  }
}