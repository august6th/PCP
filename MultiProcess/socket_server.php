<?php
/**
 * 本机进程通信:
 * Unix 系统所提供的经典进程间通信机制（管道，FIFO，共享内存， 消息队列，信号量）
 * 允许运行在同一台主机的进程之间进行通信
 *
 * 网络进程通信：
 * 通过网络相连的不同计算机上的进程间的相互通信的机制
 *
 * 套接字描述符：
 * 套接字是通信的端点的抽象，套接字描述符在 Unix 系统中被当作是一种文件描述符
 */

/**
 * 套接字服务端实现
 * 1. 创建套接字描述符：Create
 * 2. 设置套接字选项：SetOption
 * 3. 套接字与地址关联：Bind
 * 4. 监听套接字：Listen
 * 5. 接受请求：Accept
 * 6. 数据传输：Read/Write
 * 7. 关闭套接字：Close
 */

/**
 * 套接字客户端实现
 * 1. 创建套接字描述符：Create
 * 2. 设置套接字选项：SetOption
 * 3. 连接套接字：Connect
 * 4. 传输数据：Read/Write
 * 5. 关闭套接字：Close
 */

/**
 * [*可省略的步骤*]
 * Server: create -> [setOption] -> bind -> listen -> accept -> [read/write] -> close
 * Client: create -> [setOption] -> connect -> [read/write] -> close
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

while (true) {
    if (($conn = socket_accept($resource)) === false) {
        exit("Accept failed" . PHP_EOL);
    }

    $message = "Now at " . date("Y-m-d H:i:s", time());
    $bytes = socket_write($conn, $message, strlen($message));
    echo "{$bytes} bytes send success." . PHP_EOL;
    socket_close($conn);
}



