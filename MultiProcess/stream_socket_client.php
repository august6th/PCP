<?php
$conn = stream_socket_client("tcp://127.0.0.1:8080");

$message = fread($conn, 1024);

echo $message . PHP_EOL;
fclose($conn);
