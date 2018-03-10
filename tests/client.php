<?php
/**
 * Created by MsgPackPhp.
 * User: decebal.dobrica
 * Date: 3/9/18
 */

use MsgPackPhp\Client\Client;
use MsgPackPhp\Exceptions\MessagePackRPCRequestException;

require_once __DIR__.'/../vendor/autoload.php';
error_reporting(E_ERROR | E_PARSE);

function testIs($no, $a, $b)
{
    if ($a === $b) {
        echo "OK:{$no}/{$a}/{$b}\n";
    } else {
        echo "NO:{$no}/{$a}/{$b}\n";
    }
}

try {
    $client = new Client('localhost', '1985');
    testIs('test0001', 3, array_pop($client->call('hello1', 2)));
    testIs('test0001', 5, array_pop($client->call('hello2', 3)));
    try {
        $client->call('fail', "");
    } catch (MessagePackRPCRequestException $e) {
        echo "OK (proper error)\n";
    }
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
exit;
