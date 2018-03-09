<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/22/18
 */

namespace MsgPackPhp\Server;

use MsgPackPhp\EncoderTrait;
use MsgPackPhp\Exceptions\MessagePackRPCProtocolException;

trait ServerChannelTrait
{
    use EncoderTrait;

    /**
     * @param $code
     * @param $sets
     * @param $errs
     * @return string
     */
    public function serverSendObject($code, $sets, $errs)
    {
        $data    = array();
        $data[0] = 1;
        $data[1] = $code;
        $data[2] = $errs;
        $data[3] = $sets;

        $send = $this->msgpackEncode($data);

        return $send;
    }

    /**
     * @param $recv
     * @return array
     * @throws MessagePackRPCProtocolException
     */
    public function serverRecvObject($recv)
    {
        $data = $this->msgpackDecode($recv);

        if (count($data) != 4) {
            throw new MessagePackRPCProtocolException("Invalid message structure.");
        }

        $type = $data[0];
        $code = $data[1];
        $func = $data[2];
        $args = $data[3];

        if ($type != 0) {
            throw new MessagePackRPCProtocolException("Invalid message type for request: {$type}");
        }

        return array($code, $func, $args);
    }

}
