<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/20/18
 */

namespace MsgPackPhp\Client;

interface ClientChannelInterface
{
    /**
     * @param string $code
     * @param string $func
     * @param array $args
     * @return array
     */
    public function clientCallObject(string $code, string $func, array $args) : array;

    /**
     * @param $host
     * @param $port
     * @param $call
     * @return mixed
     */
    public function clientConnection(string $host, string $port, array $call) : array;

    /**
     * @param $data
     * @return ResponseInterface
     */
    public function clientRecvObject($data) : ResponseInterface;
}
