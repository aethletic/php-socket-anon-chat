<?php

use Workerman\Worker;
use PHPSocketIO\SocketIO;

date_default_timezone_set('Europe/Samara');

require_once __DIR__ . '/vendor/autoload.php';

$io = new SocketIO(3120);
$io->on('connection', function ($client) use ($io) {
    echo "new connection coming\n";

    $client->nickname = 'Кто-то #'.rand(1,1000);

    $client->on('new_message', function ($msg) use ($io, $client) {
        echo "[new_message]: $msg\n";
        $client->broadcast->emit('new_message', [
            'user' => $client->nickname,
            'msg' => $msg,
            'date' => date('d.m.Y H:i:s')
        ]);
    });

    $client->on('disconnect', function () use ($io, $client) {
        echo "user disconnected\n";
    });
});

Worker::runAll();
