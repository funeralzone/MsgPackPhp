<?php

/**
 * Created by MsgPackPhp.
 * User: decebal.dobrica
 * Date: 3/9/18
 */

use MsgPackPhp\Server\Server;

require_once __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ERROR | E_PARSE);

/**
 * Class App
 */
class App
{
    public function hello1($a)
    {
        return $a + 1;
    }

    public function hello2($a)
    {
        return $a + 2;
    }

    public function fail()
    {
        throw new Exception('hoge');
    }
}

function testIs($no, $a, $b)
{
    if ($a === $b) {
        echo "OK:{$no}/{$a}/{$b}\n";
    } else {
        echo "NO:{$no}/{$a}/{$b}\n";
    }
}

try {
    $server = new Server('1985', new App());
    echo 'Server is listening on port 1985...';
    $server->recv();
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
exit;
