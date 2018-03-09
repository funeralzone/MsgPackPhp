<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/22/18
 */

namespace MsgPackPhp\Client;

use MsgPackPhp\EncoderTrait;
use MsgPackPhp\Exceptions\MessagePackRPCNetworkException;
use MsgPackPhp\Exceptions\MessagePackRPCProtocolException;
use MsgPackPhp\Response;
use MsgPackPhp\ResponseInterface;

trait ClientChannelTrait
{
    use EncoderTrait;

    /**
     * @param string $code
     * @param string $func
     * @param array $args
     * @return array
     */
    public function clientCallObject(string $code, string $func, array $args): array
    {
        $data = array();
        $data[0] = 0;
        $data[1] = $code;
        $data[2] = $func;
        $data[3] = $args;

        return $data;
    }

    /**
     * @param string $host
     * @param string $port
     * @param array $call
     * @return array
     * @throws MessagePackRPCNetworkException
     */
    public function clientConnection(string $host, string $port, array $call): array
    {
        $size = $this->size;
        $send = $this->msgpackEncode($call);
        $sock = $this->connect($host, $port);
        if ($sock === false) {
            throw new MessagePackRPCNetworkException(error_get_last());
        }
        $puts = fputs($sock, $send);
        if ($puts === false) {
            throw new MessagePackRPCNetworkException(error_get_last());
        }
        $msg = $this->readMsg($sock, $size);
        if (!$this->reuse_connection) {
            fclose($sock);
        }

        return $msg;
    }

    /**
     * @param $data
     * @return ResponseInterface
     * @throws MessagePackRPCProtocolException
     */
    public function clientRecvObject($data): ResponseInterface
    {
        $type = $data[0];
        $code = $data[1];
        $errs = $data[2];
        $sets = $data[3];

        if ($type != 1) {
            throw new MessagePackRPCProtocolException("Invalid message type for response: {$type}");
        }

        $response = new Response();
        $response->setErrors($errs);
        $response->setMessage($sets);

        return $response;
    }
}
