<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/19/18
 *
 * Inspiration and some mechanics from:
 * https://github.com/msgpack-rpc/msgpack-rpc-php
 *
 */

namespace MsgPackPhp\Server;

use MsgPackPhp\Exceptions\MessagePackRPCNetworkException;


final class Server implements ServerInterface
{
    public $addr = 0;
    public $port = null;

    /**
     * @var ServerChannelInterface|null
     */
    public $back = null;

    public $hand = null;
    protected $_listen_socket = null;

    public function __construct($port, $hand, $back = null)
    {
        $this->back = $back == null ? new ServerBackChannel() : $back;
        $this->port = $port;
        $this->hand = $hand;
    }

    public function __destruct()
    {
        $this->closeSocket();
    }

    public function closeSocket()
    {
        if (is_resource($this->_listen_socket)) {
            socket_close($this->_listen_socket);
        }
    }

    public function recv()
    {
        try {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if (!($socket
                && socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1)
                && socket_bind($socket, $this->addr, $this->port)
                && socket_listen($socket))
            ) {
                throw new MessagePackRPCNetworkException(error_get_last());
            }
            $this->_listen_socket = $socket;
            $sockList = array($this->_listen_socket);

            // TODO : Server connection check
            // TODO : Server connection outer
            while (true) {
                $moveList = $sockList;
                $moveNums = socket_select($moveList, $w = null, $e = null, null);
                foreach ($moveList as $moveItem) {
                    if ($moveItem == $this->_listen_socket) {
                        $acptItem = socket_accept($this->_listen_socket);
                        $sockList[] = $acptItem;
                    } else {
                        $data = socket_read($moveItem, $this->back->size);

                        list($code, $func, $args) = $this->back->serverRecvObject($data);
                        $hand = $this->hand;
                        $error = null;
                        try {
                            $ret = call_user_func_array(array($hand, $func), $args);
                        } catch (\Exception $e) {
                            $ret = null;
                            $error = $e->__toString();
                        }
                        $send = $this->back->serverSendObject($code, $ret, $error);
                        socket_write($moveItem, $send);

                        unset($sockList[array_search($moveItem, $sockList)]);
                        socket_close($moveItem);
                    }
                }
            }

        } catch (\Exception $e) {
            // TODO:
        }
    }
}
