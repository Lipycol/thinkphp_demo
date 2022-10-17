<?php
namespace app\student\controller;

use app\student\model\Student as StudentModel;
use app\student\model\College as CollegeModel;
use think\Controller;
use think\facade\Env;
use think\facade\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
    // $name = 'luxuria';
    // $where = "name like 'luxuria'";
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
    if (!$uid || !$uname) {
      // $this->error('数据插入失败,缺少所需信息');
      return re_json(400, '操作失败，缺少所需信息！');
    }
    $param['uid'] = $uid;
    $param['uname'] = $uname;
    return (new StudentModel())->addData($param);
  }

  public function update() {
    $uid = $_POST['uid'];
    $uname = $_POST['uname'];
    if (!$uid || !$uname) {
      return re_json(400, '操作失败，缺少所需信息！');
    }
    $param['uid'] = $uid;
    $param['uname'] = $uname;
    return (new StudentModel())->updateData($param);
    // $data = StudentModel::get($param['uid']);
    // $data->name = $param['uname'];
    // $data->save();
    // dump($data);
    // $data = new StudentModel;
    // $data->allowField(true)
    //   ->isUpdate(true)->save([
    //   'name'=>$uname, 'id'=>$uid]);
    // dump($data);
    // StudentModel::update(['id'=>$uid, 'name'=>$uname]);
  }

  public function delete() {
    $uid = $_POST['uid'];
    if (!$uid) {
      return re_json(400, '操作失败，缺少所需信息！');
    }
    return (new StudentModel())->deleteDataModel($uid);
  }

  public function college() {
    $data = (new StudentModel())->collegeGroup();
    if ($data) {
      return re_json(200, '操作成功！', $data);
    }
    return re_json(500, '系统内部错误！');
  }

  public function collegeStu() {
    $data = (new CollegeModel())->collegeStudent();
    return re_json(200, '操作成功！', $data);
  }
  
  public function ruleTest() {
    $rule = "/^[\w\]\-@]+$/";
    $str = $_POST['str'];
    if (preg_match($rule, $str)) {
      return re_json(200, '匹配成功！');
    } else {
      return re_json(400, '匹配失败！');
    }
  }

  public function exportFile() {
    $data = (new StudentModel())->findData();
    $title = ['id', 'name', 'grade', 'college_id', 'sex'];
    $sheet = (new StudentModel())->exportData($data, $title);
  }

  public function importFile() {
    $file = $_FILES['file'];
    $fileExtendname = substr(strrchr($file['name'], '.'), 1);
    // return json($file);
    if (is_uploaded_file($file['tmp_name'])) {
      if ($fileExtendname == 'xlsx') {
        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
      } else {
        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
      }
      $filename = $file['tmp_name'];
      $objPHPExcel = $objReader->load($filename);
      $sheet = $objPHPExcel->getSheet(0);
      $highserRow = $sheet->getHighestRow();
      $data = $sheet->toArray();
      foreach ($sheet->getDrawingCollection() as $drawing) {
        // return json($file);
        list($startColumn, $startRow) = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($drawing->getCoordinates());
        $imageFileName = $drawing->getIndexedFilename();
        return $imageFileName;
      }
      return json($highserRow);
      return json($sheet->getDrawingCollection());
    }
  }
}