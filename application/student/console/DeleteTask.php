<?php
namespace app\student\console;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Exception;

class DeleteTask extends Command {
  protected function configure() {
    $this->setName('DeleteTask')
      ->addArgument('id', Argument::OPTIONAL, 'student id')
      ->setDescription('删除学生信息');
  }

  protected function execute(Input $input, Output $output) {
    try {
      Db::startTrans();
      $id = trim($input->getArgument('id'));
      $id = $id ?: 1;
      Db::name('student')->where('id', $id)
        ->delete();
      Db::commit();
    } catch(Exception $e) {
      Db::rollback();
    }
  }
}