<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 2/16/18
 */

namespace MsgPack\Tests\Example;

use MsgPackPhp\Client\ClientInterface;


/**
 * Class MsgPackService
 * @package Memoria\Application\Services\MsgPack
 */
class MsgPackServiceStub
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * MsgPackService constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function doJob(string $job, string $payload): string
    {
        $messages =  $this->client->call("SyncJob", $job, $payload);
        return array_pop($messages);
    }
}
