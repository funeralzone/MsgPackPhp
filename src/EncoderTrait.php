<?php
/**
 * Created by memoria.
 * User: decebal.dobrica
 * Date: 2/22/18
 */

namespace MsgPackPhp;

use MessagePack\Packer;

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
}
