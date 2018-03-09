<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/22/18
 */

namespace MsgPackPhp;

use MessagePack\Packer;
use MessagePack\Unpacker;

trait EncoderTrait
{
    /**
     * @param $data
     * @return string
     */
    protected function msgpackEncode($data)
    {
        $packer = new Packer(Packer::FORCE_STR);
        return $packer->pack($data);
    }

    /**
     * @param $data
     * @return array
     */
    protected function msgpackDecode($data)
    {
        return (new Unpacker())->unpack($data);
    }
}
