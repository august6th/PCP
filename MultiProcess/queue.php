<?php
/**
 * PHP 编译安装时，带上以下选项可开启额外功能
 * --enable-sysvmsg 开启消息队列
 * --enable-sysvsem 开启信号量
 * --enable-sysvshm 开启共享内存
 *
 * 如果安装了 shmop 扩展，则提供了所有 System V (包括上面三个) 的函数的封装
 */

/**
 * ftok             把路径和项目名转换为一个给进程通讯使用的整型 key 指
 * msg_queue_exists 检查一个消息队列是否存在
 * msg_get_queue    创建或获取一个消息队列
 * msg_stat_queue   返回消息队列数据结构的信息
 * msg_set_queue    设置消息队列的数据结构的信息
 * msg_send         发送一条消息到消息队列
 * msg_receive      从消息队列中接收一条消息
 * msg_remove_queue 销毁一个消息队列
 */

$key = 12345678;

$resource = msg_get_queue($key);

if ($resource === false) {
    exit('Create failed.' . PHP_EOL);
}

$stats = msg_stat_queue($resource);

msg_set_queue($resource, [
    'msg_qbytes' => 9999,
]);

var_export($stats);
echo PHP_EOL;

/**
 * 通过 msgtype 来分类消息
 */
if (!msg_send($resource, 1, "queue message.")) {
    exit("Send failed." . PHP_EOL);
}

echo "Done" . PHP_EOL;