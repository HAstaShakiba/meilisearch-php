<?php

namespace Meilisearch\Contracts;

use Meilisearch\Endpoints\Delegates\TasksQueryTrait;

class DeleteTasksQuery
{
    use TasksQueryTrait;

    /**
     * @var array
     */
    private $canceledBy;

    public function setCanceledBy(array $canceledBy)
    {
        $this->canceledBy = $canceledBy;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter(
            array_merge(
                $this->baseArray(),
                ['canceledBy' => $this->formatArray($this->canceledBy ?? null)]
            ), function ($item) { return null != $item || is_numeric($item); }
        );
    }
}
