<?php
$key = '12345678';
$resource = msg_get_queue($key);

if ($resource === false) {
    exit('Create failed.' . PHP_EOL);
}

if (!msg_receive($resource, 1, $msgtype, 1024, $message)) {
    exit("Receive failed" . PHP_EOL);
}

echo $msgtype . PHP_EOL;
echo "Message: " . $message . PHP_EOL;
echo "Done" . PHP_EOL;
