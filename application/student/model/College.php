<?php
namespace app\student\model;

use think\Model;
use think\Db;

class College extends Model {
  public function collegeStudent() {
    $data = self::with(['student'=>function($e) {
      $e->field('id, name, college_id');
      }])->field('id, name')
      ->select();
    return $data;
  }

  public function student() {
    return $this->hasMany('student');
  }
}