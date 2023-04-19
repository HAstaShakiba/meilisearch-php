<?php

namespace Tests\Http;

use Meilisearch\Exceptions\ApiException;
use Meilisearch\Exceptions\InvalidResponseBodyException;
use Meilisearch\Exceptions\JsonDecodingException;
use Meilisearch\Exceptions\JsonEncodingException;
use Meilisearch\Http\Client;
use Meilisearch\Meilisearch;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    public function testGetExecutesRequest(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');

        $client = new Client('https://localhost', null, $httpClient);
        $result = $client->get('/');

        $this->assertSame([], $result);
    }

    public function testPostExecutesRequest(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');

        $client = new Client('https://localhost', null, $httpClient);
        $result = $client->post('/');

        $this->assertSame([], $result);
    }

    public function testPostExecutesRequestWithCustomStreamFactory(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $streamFactory->expects(self::atLeastOnce())->method('createStream');

        $client = new Client('https://localhost', null, $httpClient, null, [], $streamFactory);
        $result = $client->post('/');

        $this->assertSame([], $result);
    }

    public function testPostThrowsApiException(): void
    {
        try {
            $httpClient = $this->createHttpClientMock(300, '{"message":"internal error","code":"internal"}');
            $client = new Client('https://localhost', null, $httpClient);
            $client->post('/', '');
            $this->fail('ApiException not raised.');
        } catch (ApiException $e) {
            $this->assertEquals('internal', $e->errorCode);
        }
    }

    public function testPutExecutesRequest(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');

        $client = new Client('https://localhost', null, $httpClient);
        $result = $client->put('/');

        $this->assertSame([], $result);
    }

    public function testPutThrowsApiException(): void
    {
        try {
            $httpClient = $this->createHttpClientMock(300, '{"message":"internal error","code":"internal"}');
            $client = new Client('https://localhost', null, $httpClient);
            $client->put('/', '');
            $this->fail('ApiException not raised.');
        } catch (ApiException $e) {
            $this->assertEquals('internal', $e->errorCode);
        }
    }

    public function testPatchExecutesRequest(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');

        $client = new Client('https://localhost', null, $httpClient);
        $result = $client->patch('/');

        $this->assertSame([], $result);
    }

    public function testPatchThrowsApiException(): void
    {
        try {
            $httpClient = $this->createHttpClientMock(300, '{"message":"internal error","code":"internal"}');
            $client = new Client('https://localhost', null, $httpClient);
            $client->patch('/', '');
            $this->fail('ApiException not raised.');
        } catch (ApiException $e) {
            $this->assertEquals('internal', $e->errorCode);
        }
    }

    public function testDeleteExecutesRequest(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');

        $client = new Client('https://localhost', null, $httpClient);
        $result = $client->delete('/');

        $this->assertSame([], $result);
    }

    public function testInvalidResponseContentTypeThrowsException(): void
    {
        $httpClient = $this->createHttpClientMock(200, '<b>not json</b>', 'text/html');

        $client = new Client('https://localhost', null, $httpClient);

        $this->expectException(InvalidResponseBodyException::class);
        $this->expectExceptionMessage('not json');

        $client->get('/');
    }

    public function testClientHasDefaultUserAgent(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');
        $reqFactory = $this->createMock(RequestFactoryInterface::class);
        $requestStub = $this->createMock(RequestInterface::class);

        /* @phpstan-ignore-next-line */
        $requestStub->expects($this->any())
            ->method('withAddedHeader')
            ->withConsecutive(
                [
                    $this->equalTo('User-Agent'),
                    $this->equalTo(Meilisearch::qualifiedVersion()),
                ],
                [
                    $this->equalTo('Authorization'),
                    $this->equalTo('Bearer masterKey'),
                ]
            )
            ->willReturnOnConsecutiveCalls($requestStub, $requestStub);

        $reqFactory->expects($this->any())
            ->method('createRequest')
            ->willReturn($requestStub);

        $client = new \Meilisearch\Client('http://localhost:7070', 'masterKey', $httpClient, $reqFactory);

        $client->health();
    }

    public function testClientHasCustomUserAgent(): void
    {
        $customAgent = 'Meilisearch Symfony (v0.10.0)';
        $httpClient = $this->createHttpClientMock(200, '{}');
        $reqFactory = $this->createMock(RequestFactoryInterface::class);
        $requestStub = $this->createMock(RequestInterface::class);

        /* @phpstan-ignore-next-line */
        $requestStub->expects($this->any())
            ->method('withAddedHeader')
            ->withConsecutive(
                [
                    $this->equalTo('User-Agent'),
                    $this->equalTo($customAgent.';'.Meilisearch::qualifiedVersion()),
                ],
                [
                    $this->equalTo('Authorization'),
                    $this->equalTo('Bearer masterKey'),
                ]
            )
            ->willReturnOnConsecutiveCalls($requestStub, $requestStub);

        $reqFactory->expects($this->any())
            ->method('createRequest')
            ->willReturn($requestStub);

        $client = new \Meilisearch\Client('http://localhost:7070', 'masterKey', $httpClient, $reqFactory, [$customAgent]);

        $client->health();
    }

    public function testClientHasEmptyCustomUserAgentArray(): void
    {
        $httpClient = $this->createHttpClientMock(200, '{}');
        $reqFactory = $this->createMock(RequestFactoryInterface::class);
        $requestStub = $this->createMock(RequestInterface::class);

        /* @phpstan-ignore-next-line */
        $requestStub->expects($this->any())
            ->method('withAddedHeader')
            ->withConsecutive(
                [
                    $this->equalTo('User-Agent'),
                    $this->equalTo(Meilisearch::qualifiedVersion()),
                ],
                [
                    $this->equalTo('Authorization'),
                    $this->equalTo('Bearer masterKey'),
                ]
            )
            ->willReturnOnConsecutiveCalls($requestStub, $requestStub);

        $reqFactory->expects($this->any())
            ->method('createRequest')
            ->willReturn($requestStub);

        $client = new \Meilisearch\Client('http://localhost:7070', 'masterKey', $httpClient, $reqFactory, []);

        $client->health();
    }

    public function testParseResponseReturnsNullForNoContent(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(204);

        /** @var ClientInterface|MockObject $httpClient */
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->with(self::isInstanceOf(RequestInterface::class))
            ->willReturn($response);

        $client = new Client('https://localhost', null, $httpClient);

        $result = $client->get('/');

        $this->assertNull($result);
    }

    /**
     * @return ClientInterface|MockObject
     */
    private function createHttpClientMock(int $status = 200, string $content = '{', string $contentType = 'application/json')
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->expects(self::once())
            ->method('getContents')
            ->willReturn($content);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())
            ->method('getStatusCode')
            ->willReturn($status);
        $response->expects(self::any())
            ->method('getHeader')
            ->with('content-type')
            ->willReturn([$contentType]);
        $response->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->with(self::isInstanceOf(RequestInterface::class))
            ->willReturn($response);

        return $httpClient;
    }
}
