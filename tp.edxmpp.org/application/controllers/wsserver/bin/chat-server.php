<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;
use React\Socket\TcpServer;

use MyApp\Chat;


require dirname(__DIR__) . '/vendor/autoload.php';





////////////////////////////// sample 1 //////////////////////////////
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);
$server->run();
////////////////////////////// sample 1 //////////////////////////////





////////////////////////////// sample 2 //////////////////////////////
//$loop = factory::create();
//
//$webSock = new Server('0.0.0.0:8080', $loop);
//$webSock = new SecureServer(
//    $webSock,
//    $loop,
//    array(
//        'local_cert' => '../../conf/ssl.crt/server.crt',
//        'local_pk' => '../../conf/ssl.key/server.key',
//    )
//);
//
//// Ratchet magic
//$webServer = new IoServer(
//    new HttpServer(
//        new WsServer(
//            new Chat()
//        )
//    ),
//    $webSock
//);
//$loop->run();
////////////////////////////// sample 2 //////////////////////////////


//'C:\xampp\apache\conf\ssl.crt\ca_bundle.crt',
//'C:\xampp\apache\conf\ssl.crt\server.crt',
// 'C:\xampp\apache\conf\ssl.key\server.key'
