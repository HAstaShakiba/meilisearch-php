<?php


namespace Meilisearch\Contracts;

class DocumentsQuery
{
    /**
     * @var int
     */
    private $offset;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var array
     */
    private $fields;

    public function setOffset(int $offset): DocumentsQuery
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit): DocumentsQuery
    {
        $this->limit = $limit;

        return $this;
    }

    public function setFields(array $fields): DocumentsQuery
    {
        $this->fields = $fields;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'offset' => $this->offset ?? null,
            'limit' => $this->limit ?? null,
            'fields' => isset($this->fields) ? implode(',', $this->fields) : null,
        ], function ($item) { return null != $item || is_numeric($item); });
    }
}
