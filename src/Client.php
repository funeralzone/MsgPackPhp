<?php
/**
 * Created by memoria.
 * User: decebal.dobrica
 * Date: 2/19/18
 *
 * Inspiration and some mechanics from:
 * https://github.com/msgpack-rpc/msgpack-rpc-php
 *
 */

namespace MsgPackPhp;

use MsgPackPhp\Exceptions\MessagePackRPCRequestException;

class Client implements ClientInterface
{
    private $back;
    private $host;
    private $port;

    /**
     * Client constructor.
     *
     * @param string $host
     * @param int $port
     * @param ClientChannelInterface|null $back
     */
    public function __construct(string $host, int $port, ClientChannelInterface $back = null)
    {
        $this->back = $back == null ? new BackChannel() : $back;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @param string $func
     * @param array $args
     * @return array
     * @throws MessagePackRPCRequestException
     */
    protected function send(string $func, array $args) : array
    {
        $host = $this->host;
        $port = $this->port;
        $code = 0;
        $call = $this->back->clientCallObject($code, $func, $args);
        $messages = $this->back->clientConnection($host, $port, $call);
        $result = [];
        foreach ($messages as $message) {
            $response = $this->back->clientRecvObject($message);
            $result[] = $response->getMessage();
            $errors = $response->getErrors();

            if (!is_null($errors)) {
                if (is_array($errors)) {
                    $errors = '[' . implode(', ', $errors) . ']';
                } else {
                    if (is_object($errors)) {
                        if (method_exists($errors, '__toString')) {
                            $errors = $errors->__toString();
                        } else {
                            $errors = print_r($errors, true);
                        }
                    }
                }
                throw new MessagePackRPCRequestException("{$errors}");
            }
        }

        return $result;
    }

    /**
     * @param string $func
     * @param array $args
     * @return array
     */
    public function call(string $func, ...$args) : array
    {
        return $this->send($func, $args);
    }
}
