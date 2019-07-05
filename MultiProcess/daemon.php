<?php
echo "Master process id is " . posix_getpid() . PHP_EOL;

/**
 * 1. 创建子进程，终止父进程
 * 2. 在子进程中创建新会话
 * 3. 改变工作目录（默认继承了当前父进程的工作目录）
 * 4. 重设文件创建掩码（默认继承了当前父进程的文件创建掩码）
 * 5. 关闭文件描述符（默认继承了父进程打开的文件）
 */
function daemon()
{
    // 1-1 创建子进程
    $pid = pcntl_fork();
    switch ($pid) {
        case -1:
            die("Create Failed");
            break;
        case 0:
            // 2 posix_setsid 让当前进程变为主会话
            if (($sid = posix_setsid()) <= 0) {
                die("Set sid failed" . PHP_EOL);
            }
            // 3 chdir 改变工作目录
            if (chdir("/") === false) {
                die("Change work directory failed" . PHP_EOL);
            }

            // 4 改变当前的 umask 为最宽松的掩码
            umask(0);

            // 5 fclose 关闭一个一打开的文件指针
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);

            break;
        default:
            // 1-2 终止父进程
            exit;
            break;
    }
}

function fork()
{
    global $children;
    $pid = pcntl_fork();
    switch ($pid) {
        case -1:
            exit("Create Failed");
            break;
        case 0:
            while (true) {
                sleep(10);
            }
            break;
        default:
            $children[$pid] = $pid;
    }
}

daemon();
$children = [];
$count = 3;

for($i = 0; $i < $count; $i++) {
    fork();
}

while (count($children)) {
    if (($exitId = pcntl_wait($status)) > 0) {
        unset($children[$exitId]);
    }
    if (count($children) < $count) {
        fork();
    }
}


