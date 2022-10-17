<?php

namespace app\student\console;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class TaskTest extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('tasktest');
        // 设置参数
        
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln('tasktest');
    }
}
