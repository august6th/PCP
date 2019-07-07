<?php
/**
 * streams 是对协议和网络通信更高级别的封装，操作更为方便
 */
if (($resource = stream_socket_server("tcp://0.0.0.0:8080")) === false) {
    exit("Create failed." . PHP_EOL);
}

while (true) {
    $conn = stream_socket_accept($resource, -1);
    $message = "Now at " . date("Y-m-d H:i:s", time());
    $bytes = fwrite($conn, $message);
    if ($bytes === false) {
        exit("Write failed." . PHP_EOL);
    }
    echo "{$bytes} bytes send success." . PHP_EOL;
    fclose($conn);
}