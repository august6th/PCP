<?php
include_once __DIR__ . "/../vendor/autoload.php";

use Goutte\Client;
$client = new Client();
$links = [
    "http://www.nipic.com/topic/show_27051_1.html",
    "http://www.nipic.com/topic/show_27202_1.html",
    "http://www.nipic.com/topic/show_27055_1.html",
];

$children = [];
foreach ($links as $url) {
    $pid = pcntl_fork();
    switch ($pid) {
        case -1:
            exit("Create failed" . PHP_EOL);
            break;
        case 0:
            $data = [];
            $id = posix_getpid();
            $crawler = $client->request("GET", $url);
            $crawler->filter(".search-works-thumb")->each(function ($node) use ($client, $id, &$data) {
                $url = $node->link()->getUri();
                $crawler = $client->request("GET", $url);
                $crawler->filter("#J_worksImg")->each(function ($node) use ($id, &$data) {
                    $src = $node->image()->getUri();
                    $data[$id][] = $src;
                });
            });
            print_r($data);
            exit;
            break;
        default:
            $children[$pid] = $pid;
            break;
    }
}
while (count($children)) {
    if (($id = pcntl_wait($status, WUNTRACED)) > 0) {
        unset($children[$id]);
    }
}
echo "Done" . PHP_EOL;
