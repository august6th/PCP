<?php
/**
 * sem_get      得到一个信号量 id
 * sem_acquire  获取一个信号量
 * sem_release  释放一个信号量
 * sem_remove   移除一个信号量
 */

$key = 123456;

$resource = sem_get($key);

if ($resource === false) {
    exit("Get sem failed" . PHP_EOL);
}

if (sem_acquire($resource)) {
    echo "Sem acquire success" . PHP_EOL;
    echo "Doing something..." . PHP_EOL;
    // 手动释放此信号量
    sem_release($resource);
    sleep(10);
}

echo "Done" . PHP_EOL;