<?php

namespace Meilisearch\Http\Serialize;

class Json implements SerializerInterface
{
    /**
     * {@inheritDoc}
     */
    public function serialize($data)
    {
        return json_encode($data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize(string $string)
    {
        return json_decode($string, true);
    }
}
