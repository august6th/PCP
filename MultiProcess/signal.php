<?php
function daemon()
{
    $pid = pcntl_fork();
    switch ($pid) {
        case -1:
            die("Create Failed");
            break;
        case 0:
            if (($sid = posix_setsid()) <= 0) {
                die("Set sid failed" . PHP_EOL);
            }
            pcntl_signal(SIGTERM, SIG_IGN, false);
            if (chdir("/") === false) {
                die("Change work directory failed" . PHP_EOL);
            }
            umask(0);
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);
            break;
        default:
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
            pcntl_signal(SIGTERM, SIG_IGN, false);
            while (true) {
                sleep(10);
            }
            break;
        default:
            $children[$pid] = $pid;
    }
}

$arg = $_SERVER['argv']['1'] ?? '';
$masterIdFile = '/tmp/master_id';

switch ($arg) {
    case 'start':
        if (file_exists($masterIdFile)) {
            exit("Process already started" . PHP_EOL);
        }
        break;
    case 'reload':
        $masterId = file_get_contents($masterIdFile);
        exec("ps --ppid {$masterId} | awk '/[0-9]/{print $1}' | xargs", $output, $status);
        if ($status == 0) {
            $childIds = explode(' ', current($output));
            foreach ($childIds as $childId) {
                posix_kill($childId, SIGKILL);
            }
        }
        exit;
        break;
    case 'stop':
        $masterId = file_get_contents($masterIdFile);
        exec("ps --ppid {$masterId} | awk '/[0-9]/{print $1}' | xargs", $output, $status);
        if ($status == 0) {
            posix_kill($masterId, SIGKILL);
            $childIds = explode(' ', current($output));
            foreach ($childIds as $childId) {
                posix_kill($childId, SIGKILL);
            }
        }
        while (true) {
            if (!posix_kill($masterId, 0)) {
                @unlink($masterIdFile);
                break;
            }
        }
        exit;
        break;
    default:
        exit("Please enter command!" . PHP_EOL);
}

daemon();
$children = [];
$count = 3;

$masterId = posix_getpid();
file_put_contents($masterIdFile, $masterId);

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


