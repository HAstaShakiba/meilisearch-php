<?php

namespace Meilisearch;

use Meilisearch\Endpoints\Delegates\HandlesDumps;
use Meilisearch\Endpoints\Delegates\HandlesIndex;
use Meilisearch\Endpoints\Delegates\HandlesKeys;
use Meilisearch\Endpoints\Delegates\HandlesMultiSearch;
use Meilisearch\Endpoints\Delegates\HandlesSystem;
use Meilisearch\Endpoints\Delegates\HandlesTasks;
use Meilisearch\Endpoints\Dumps;
use Meilisearch\Endpoints\Health;
use Meilisearch\Endpoints\Indexes;
use Meilisearch\Endpoints\Keys;
use Meilisearch\Endpoints\Stats;
use Meilisearch\Endpoints\Tasks;
use Meilisearch\Endpoints\TenantToken;
use Meilisearch\Endpoints\Version;
use Meilisearch\Http\Client as MeilisearchClientAdapter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Client
{
    use HandlesDumps;
    use HandlesIndex;
    use HandlesTasks;
    use HandlesKeys;
    use HandlesSystem;
    use HandlesMultiSearch;

    /**
     * @var \Meilisearch\Contracts\Http|\Meilisearch\Http\Client
     */
    private $http;
    /**
     * @var \Meilisearch\Endpoints\Indexes
     */
    private $index;
    /**
     * @var \Meilisearch\Endpoints\Health
     */
    private $health;
    /**
     * @var \Meilisearch\Endpoints\Version
     */
    private $version;
    /**
     * @var \Meilisearch\Endpoints\Keys
     */
    private $keys;
    /**
     * @var \Meilisearch\Endpoints\Stats
     */
    private $stats;
    /**
     * @var \Meilisearch\Endpoints\Tasks
     */
    private $tasks;
    /**
     * @var \Meilisearch\Endpoints\Dumps
     */
    private $dumps;
    /**
     * @var \Meilisearch\Endpoints\TenantToken
     */
    private $tenantToken;

    public function __construct(
        string $url,
        string $apiKey = null,
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        array $clientAgents = [],
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->http = new MeilisearchClientAdapter($url, $apiKey, $httpClient, $requestFactory, $clientAgents, $streamFactory);
        $this->index = new Indexes($this->http);
        $this->health = new Health($this->http);
        $this->version = new Version($this->http);
        $this->stats = new Stats($this->http);
        $this->tasks = new Tasks($this->http);
        $this->keys = new Keys($this->http);
        $this->dumps = new Dumps($this->http);
        $this->tenantToken = new TenantToken($this->http, $apiKey);
    }
}
