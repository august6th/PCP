<?php
/**
 * 创建一个类型为 pipe 的文件
 */
$filePath = '/tmp/var/fifo';

if (file_exists($filePath)) {
    exit('fifo is already exists' . PHP_EOL);
}

if (!posix_mkfifo($filePath, 0664)) {
    exit('make fifo failed' . PHP_EOL);
}

$handle = fopen($filePath, 'w');

$i = 0;
while (true) {
    $i++;
    sleep(1);
    fwrite($handle, "({$i})");
    if ($i == 10) {
        fclose($handle);
        break;
    }
}

