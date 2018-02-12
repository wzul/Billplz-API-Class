<?php

namespace Billplz;

class API
{
    private $connect;

    public function __construct(\Billplz\Connect $connect)
    {
        $this->connect = $connect;
    }

    public function setConnect(\Billplz\Connect $connect)
    {
        $this->connect = $connect;
    }

    public function getCollectionIndex(array $parameter)
    {
        return $this->connect->getCollectionIndex($parameter);
    }

    public function createCollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->createCollectionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->createCollection($parameter);
        }

        throw new Exception('Create Collection Error!');
    }

    public function toArray(array $json)
    {
        return $this->connect->toArray($json);
    }

    public function test()
    {
        if (\class_exists('\GuzzleHttp\Client')) {
        }
        if (\class_exists('\GuzzleHttp\Exception\ClientException')) {
        }
    }
}
