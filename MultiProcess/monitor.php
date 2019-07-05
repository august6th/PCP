<?php
echo "Master process id is " . posix_getpid() . PHP_EOL;


$children = [];
function fork()
{
    global $children;
    $pid = pcntl_fork();
    switch ($pid) {
        case -1:
            exit("Create Failed");
            break;
        case 0:
            echo "Child process id is " . posix_getpid() . PHP_EOL;
            while (true) {
                sleep(10);
            }
            break;
        default:
            $children[$pid] = $pid;
    }
}

$count = 3;

for($i = 0; $i < $count; $i++) {
    fork();
}

while (count($children)) {
    if ($exitId = pcntl_wait($status)) {
        echo "Child($exitId) exit" . PHP_EOL;
        echo "中断子进程的信号值为: " . pcntl_wtermsig($status) . PHP_EOL;
        unset($children[$exitId]);
    }
    if (count($children) < $count) {
        fork();
    }
}


