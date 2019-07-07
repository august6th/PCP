<?php
/**
 * 管道是 Unix 系统进程通信最古老的方式，所有 Unix 系统都提供此种通信机制
 * 管道分类：
 * 1. 普通管道：无文件，只能在两个相关的进程间使用，且有相同的祖先进程
 * 2. 有名管道(FIFO)：系统中有可见的管道文件，不想关的进程也能交换文件
 */

/**
 * popen        打开进程文件指针（单向管道）
 * pclose       关闭 popen 打开的指向管道的文件指针
 * proc_open    执行一个命令，并且打开用来输入/输出的文件指针（双向管道）
 * proc_close   关闭由 proc_open 打开的进程，并返回退出码
 *
 * posix_mkfifo 创建一个 fifo 文件（有名管道）
 */


/**
 * cmd 应用到管道内容的命令
 */
$cmd = 'cat';

/**
 * desc 对管道描述符的描述
 * 索引数组，key 代表是标准输入、标准输出或是标准错误
 * 0 => 标准输入，描述符的类型有 pipe 和 file 两个值， r 代表读
 * 1 => 标准输出，描述符的类型有 pipe 和 file 两个值， w 代表写
 * 2 => 标准错误，描述符的类型有 pipe 和 file 两个值， a 代表追加写
 */
$desc = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['file', '/tmp/proc_open_err.log', 'a'],
];


$handles = [];
for ($i = 0; $i < 3; $i++) {
    /**
     * pipes 为一个引用变量
     * 打开的管道的文件指针都会保存在 pipes 里面
     * 管道实际上也是一个文件
     */
    $handle = proc_open($cmd, $desc, $pipes);
    sleep(2);
    fwrite($pipes[0], date("Y-m-d H:i:s", time()));
    $handles[] = [
        'handle' => $handle,
        'output' => $pipes[1],
    ];

    //关闭读
    fclose($pipes[0]);
}

foreach ($handles as $handle) {
    echo fread($handle['output'], 1024) . PHP_EOL;
    fclose($handle['output']);
    proc_close($handle['handle']);
}
