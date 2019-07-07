<?php
if (($resource = socket_create(AF_INET, SOL_SOCKET, SOL_TCP)) === false) {
    exit("Create socket failed" . PHP_EOL);
}

if (!socket_set_option($resource, SOL_SOCKET, SO_REUSEPORT, true)) {
    exit("Set option failed" . PHP_EOL);
}

if (!socket_connect($resource, "127.0.0.1", 8080)) {
    exit("Connect failed" . PHP_EOL);
}

$message = socket_read($resource, 1024);
echo $message . PHP_EOL;

socket_close($resource);
