<?php
/**
 * Created by MsgPackPhp.
 * User: decebal.dobrica
 * Date: 3/9/18
 */

namespace MsgPackPhp\Server;


final class ServerBackChannel implements ServerChannelInterface
{
    use ServerChannelTrait;

    public $size = 1024;

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
    }
}
