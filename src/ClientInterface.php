<?php
/**
 * Created by memoria.
 * User: decebal.dobrica
 * Date: 2/20/18
 */

namespace MsgPackPhp;

interface ClientInterface
{
    /**
     * @param string $func
     * @param array ...$args
     * @return array
     */
    public function call(string $func, ...$args) : array;
}
