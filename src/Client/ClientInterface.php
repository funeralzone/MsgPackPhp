<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/20/18
 */

namespace MsgPackPhp\Client;

interface ClientInterface
{
    /**
     * @param string $func
     * @param array ...$args
     * @return array
     */
    public function call(string $func, ...$args) : array;
}
