<?php
/**
 * Created by memoria.
 * User: decebal.dobrica
 * Date: 2/19/18
 */

namespace MsgPackPhp;

use MsgPackPhp\Exceptions\MessagePackRPCNetworkException;
use MessagePack\BufferUnpacker;

/**
 * Class BackChannel
 * @package MsgPackPhp
 */
class BackChannel implements ClientChannelInterface
{
    use ClientChannelTrait;

    public $size = 1024;
    public static $shared_client_socket = null;
    public static $allow_persistent = false;
    public $client_socket = null;
    public $use_shared_connection = true;
    public $reuse_connection = true;
    protected static $shared_unpacker = null;
    protected $unpacker = null;

    /**
     * BackChannel constructor.
     *
     * @param array $opts
     * @param array $opts_compat
     */
    public function __construct($opts = array(), $opts_compat = array())
    {
        if (!is_array($opts)) {
            $opts = array('size' => $opts);
        }
        $opts = array_merge($opts, $opts_compat);
        if (array_key_exists('size', $opts)) {
            $this->size = $opts['size'];
        }
        if (array_key_exists('reuse_connection', $opts)) {
            $this->reuse_connection = $opts['reuse_connection'];
        }
        if (array_key_exists('use_shared_connection', $opts)) {
            $this->use_shared_connection = $opts['use_shared_connection'];
        }

        if (self::$allow_persistent) {
            $this->use_shared_connection = true;
            $this->reuse_connection = true;
        }

        if ($this->use_shared_connection) {
            if (!self::$shared_unpacker) {
                self::$shared_unpacker = new BufferUnpacker();
            }
            $this->unpacker = self::$shared_unpacker;
        } else {
            $this->unpacker = new BufferUnpacker();
        }
    }

    /**
     * close resource sockets/connections
     */
    public function __destruct()
    {
        if (!self::$allow_persistent) {
            if (self::$shared_client_socket) {
                fclose(self::$shared_client_socket);
            }
            if ($this->client_socket) {
                fclose($this->client_socket);
            }
        }
    }

    /**
     * @param $io
     * @param $size
     * @return mixed
     * @throws MessagePackRPCNetworkException
     */
    private function readMsg($io, $size)
    {
        stream_set_blocking($io, 0);

        while (!feof($io)) {
            $r = array($io);
            $n = null;
            stream_select($r, $n, $n, null);
            $read = fread($io, $size);
            if ($read === false) {
                throw new MessagePackRPCNetworkException(error_get_last());
            }
            $this->unpacker->append($read);
            $unpackedBlocks = $this->unpacker->tryUnpack();
            if ($unpackedBlocks) {
                return $unpackedBlocks;
            }
        }

        throw new MessagePackRPCNetworkException("Could not read the server response!");
    }

    /**
     * @param $host
     * @param $port
     * @return mixed|null
     */
    private function connect($host, $port)
    {
        if (!$this->reuse_connection) {
            return $this->sockopen($host, $port);
        }
        $sock = $this->use_shared_connection ? self::$shared_client_socket : $this->client_socket;
        if ($sock && !feof($sock)) {
            return $sock;
        }
        if (!$sock) {
            $sock = $this->sockopen($host, $port);
        } elseif (feof($sock)) {
            $sock = $this->sockopen($host, $port);
        }
        if ($this->use_shared_connection) {
            self::$shared_client_socket = $sock;
        } else {
            $this->client_socket = $sock;
        }
        return $sock;
    }

    /**
     * @param $host
     * @param $port
     * @return mixed
     */
    private function sockopen($host, $port)
    {
        $method = self::$allow_persistent ? 'pfsockopen' : 'fsockopen';
        return call_user_func($method, $host, $port);
    }
}
