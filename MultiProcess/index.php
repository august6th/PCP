<?php
echo "Master process id is " . posix_getpid() . PHP_EOL;

$pid = pcntl_fork();

switch ($pid) {
    case -1:
        exit("Create Failed");
        break;
    case 0:
        echo "Child process id is " . posix_getpid() . PHP_EOL;
        sleep(5);
        echo "Child process die at " . time() . PHP_EOL;
        break;
    default:
//        sleep(20);
        if ($exitId = pcntl_waitpid($pid, $status, WUNTRACED)) {
            echo "Child {$exitId} is exists" . PHP_EOL;
        }
        echo "Master process die at " . time() . PHP_EOL;
}
