<?php

namespace Tests\Http\Serialize;

use Meilisearch\Exceptions\JsonDecodingException;
use Meilisearch\Exceptions\JsonEncodingException;
use Meilisearch\Http\Serialize\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testSerialize(): void
    {
        $data = ['id' => 287947, 'title' => 'Some ID'];
        $json = new Json();
        $this->assertEquals(json_encode($data), $json->serialize($data));
    }

    public function testUnserialize(): void
    {
        $data = '{"id":287947,"title":"Some ID"}';
        $json = new Json();
        $this->assertEquals(['id' => 287947, 'title' => 'Some ID'], $json->unserialize($data));
    }
}
