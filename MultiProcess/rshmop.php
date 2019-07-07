<?php
/**
 * 共享内存：
 * 指在多处理器的计算机系统中，可以被不同 cpu 访问的大容量内存，是 unix 系统下
 * 多进程之间的通信方法，程序间通过共享内存来传递信息
 *
 * 特点：
 * 数据不需要在客户进程和服务器之间复制，所以是最快的一种进程通信
 */

/**
 * 依靠 shmop 扩展
 * shmop_open       创建或打开共享内存块
 * shmop_close      关闭共享内存块
 * shmop_size       取得共享内存块大小
 * shmop_write      写入数据到共享内存块
 * shmop_read       从共享内存块读数据
 * shmop_delete     删除共享内存块
 */


$resource = shmop_open(0x7427047a, 'c', 0664, 200);
//echo shmop_read($resource, 0, 200) . PHP_EOL;

shmop_delete($resource);

