<?php
namespace app\student\model;

use think\Db;
use think\Model;
use think\Exception;
use think\facade\Env;
use think\facade\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Student extends Model {
  public function findData() {  // 数据库方法
    // $data = $this->alias('stu')
    //   ->field("stu.name, (case stu.sex when 1 then '男' else '女' end), col.name as college_name")
    //   ->leftJoin('college col', 'col.id=stu.college_id')
    //   ->order('stu.id', 'asc')
    //   ->page(3, 3)
    //   ->select();
    // $data = $this->where('id', 1)->find();
    $data = $this->select()->toArray();
    return $data;
  }

  public function findDataModel() { // 模型方法
    // $data = $this->get(1);
    $data = $this->all();
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
    Db::startTrans();
    try {
      $add['id'] = $param['uid'];
      $add['name'] = $param['uname'];
      $this->insert($add);
      Db::commit();
    } catch (Exception $e) {
      Db::rollback();
      return re_json(400, '操作失败，已存在此数据！');
    }
    return re_json(200, '操作成功！');
  }

  public function addDataModel($param) {
    Db::startTrans();
    try {
      $add['id'] = $param['uid'];
      $add['name'] = $param['uname'];
      $this->create($add);
      Db::commit();
    } catch (Exception $e) {
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
      $update['name'] = $param['uname'];
      $this->where('id', $param['uid'])
        ->update($update);
      Db::commit();
    } catch (Exception $e) {
      Db::rollback();
      return re_json(400, '操作失败，不存在此数据！');
    }
    return re_json(200, '操作成功！');
  }

  public function updateDataModel($param) {
    // Db::startTrans();
    // try {
    //   $data = $this->get($param['uid']);
    //   $data['name'] = $param['uname'];
    //   $data->force()->save();
    //   Db::commit();
    // } catch (Exception $e) {
    //   Db::rollback();
    //   return re_json(400, '操作失败，不存在此数据！');
    // }
    // $data = $this->get($param['uid']);
    // $data['name'] = $param['uname'];
    // $data->force()->save();
    // $this->save([
    //   'name' => $param['uname']
    // ], ['id' => $param['uid']]);
    $this->update(['id'=>$param['uid'], 'name'=>$param['uname'],
    'grade'=>'大一', 'college_id'=>3, 'sex'=>0]);
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
    } catch (Exception $e) {
      return re_json(400, '操作失败，不存在此数据！');
    }
    return re_json(200, '操作成功！');
  }

  public function deleteDataModel($param) {
    Db::startTrans();
    try {
      // $this->destroy($param);
      $data = $this->getOrFail($param);
      $data->delete();
      Db::commit();
    } catch (Exception $e) {
      return re_json(400, '操作失败，不存在此数据！');
    }
    return re_json(200, '操作成功！');
  }

  public function exportData($data, $title) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(35);

    $titCol = 'A';
    foreach ($title as $key => $value) {
      $sheet->setCellValue($titCol.'1', $value);
      $titCol++;
    }
    
    $row = 2;
    foreach ($data as $key => $item) {
      $dataCol = 'A';
      foreach ($item as $value) {
        $sheet->setCellValue($dataCol.$row, $value);
        $dataCol++;
      }
      $row++;
    }

    $path = Env::get('root_path'); //找到当前脚本所在路径
    $new_file = $path.'public/upload/xlsx/';
    if (!file_exists($new_file))
      {
      //检查是否有该文件夹，如果没有就创建，并给予最高权限
        mkdir($new_file, 0777,true);
      }
    $file_name = 'test.xlsx';  
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment; filename="01simple.xlsx"');
    // header('Cache-Control: max-age=0');
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("upload/xlsx/".$file_name);
    exit();
  }
}