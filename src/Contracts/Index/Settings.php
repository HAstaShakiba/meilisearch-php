<?php

namespace Meilisearch\Contracts\Index;

use Meilisearch\Contracts\Data;

class Settings extends Data implements \JsonSerializable
{
    public function __construct(array $data = [])
    {
        $data['synonyms'] = new Synonyms($data['synonyms'] ?? []);
        $data['typoTolerance'] = new TypoTolerance($data['typoTolerance'] ?? []);
        parent::__construct($data);
    }

    public function jsonSerialize(): array
    {
        return $this->getIterator()->getArrayCopy();
    }
}
