<?php


namespace Meilisearch\Contracts;

class KeysQuery
{
    /**
     * @var int
     */
    private $offset;
    /**
     * @var int
     */
    private $limit;

    public function setOffset(int $offset): KeysQuery
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit): KeysQuery
    {
        $this->limit = $limit;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'offset' => $this->offset ?? null,
            'limit' => $this->limit ?? null,
        ], function ($item) { return null != $item || is_numeric($item); });
    }
}
