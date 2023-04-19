<?php

namespace Meilisearch\Endpoints\Delegates;

trait HandlesDumps
{
    public function createDump(): array
    {
        return $this->dumps->create();
    }
}
