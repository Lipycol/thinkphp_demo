<?php
namespace app\student\console;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Exception;

class DeleteTask extends Command {
  protected function configure() {
    $this->setName('DeleteTask')
      ->setDescription('删除学生信息');
  }

  protected function execute(Input $input, Output $output) {
    try {
      Db::startTrans();
      Db::name('student')
        ->order('id')
        ->limit(3)
        ->delete();
      Db::commit();
    } catch(Exception $e) {
      Db::rollback();
    }
  }
}