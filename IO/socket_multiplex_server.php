<?php
/**
 * IO 多路复用
 * 对当前的进程复用，来对多个描述符进行事件监听
 */

/**
 * 执行过程
 * 构造一张感兴趣的描述符列表
 * 然后调用一个函数（I/O 多路复用函数）
 * 直到其中一个描述符已经准备好进行 I/O 时，该函数就返回
 *
 * select，poll，epoll
 * 使我们能执行 I/O 多路复用
 * 这些函数返回时，进程会被告知哪些描述符已经准备好，可以进行 I/O 了
 *
 * 可以避免大量的空轮询
 */

/**
 * select 方式的 I/O 多路复用
 * 扩展支持：socket, stream, event
 * 监控多个文件描述符 (read, write, except), 等待一个或多个文件描述符变成就绪状态后进行 I/O 操作
 * select 缺点是只能监控少于 FD_SETSIZE（一般是 1024） 的文件描述符，所以不适合大流量，高并发的场景
 */

/**
 * poll 方式的 I/O 多路复用
 * 扩展支持：event
 * 在文件描述符上等待一些事件，和 select 执行的任务类似，监控的文件描述符集的存储方式不一样
 * 没有 FD_SETSIZE 的限制
 */

/**
 * epoll 方式的 I/O 多路复用
 * 扩展支持：event
 * I/O 事件通知能力，和 poll 执行的任务类似，支持边缘触发和水平触发
 * 对于大量监控描述符可以很好的扩展。
 * 使用水平触发时，语义和 poll 一致
 * 边缘触发针对更高性能
 */


if (($resource = socket_create(AF_INET, SOL_SOCKET, SOL_TCP)) === false) {
    exit("Create socket failed" . PHP_EOL);
}

if (!socket_set_option($resource, SOL_SOCKET, SO_REUSEPORT, true)) {
    exit("Set option failed" . PHP_EOL);
}

if (!socket_bind($resource, "0.0.0.0", 8080)) {
    exit("Bind failed" . PHP_EOL);
}

if (!socket_listen($resource, 10)) {
    exit("Listen failed" . PHP_EOL);
}

$read = $write = $expect = [];

$client = [$resource];

while (true) {
    $read = $client;
    socket_select($read, $write, $expect, null);

    foreach ($read as $fd) {
        if ($fd == $resource) {
            if (($conn = socket_accept($fd)) === false) {
                exit("Accept failed" . PHP_EOL);
            }
            $client[] = $conn;
            echo "New Client Accept." . PHP_EOL;
        } else {
            echo @socket_read($fd, 100);
        }
    }
}