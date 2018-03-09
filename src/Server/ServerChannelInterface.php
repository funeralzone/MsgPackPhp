<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/20/18
 */

namespace MsgPackPhp\Server;

use MsgPackPhp\Exceptions\MessagePackRPCProtocolException;

interface ServerChannelInterface
{
    /**
     * @param $code
     * @param $sets
     * @param $errs
     * @return string
     */
    public function serverSendObject($code, $sets, $errs);

    /**
     * @param $recv
     * @return array
     * @throws MessagePackRPCProtocolException
     */
    public function serverRecvObject($recv);
}
