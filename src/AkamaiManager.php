<?php

namespace LaravelAkamai;

use GuzzleHttp\Exception\ClientException;
use LaravelAkamai\Responses\PurgeUrlResponse;

class AkamaiManager
{
    /** @var AkamaiClient $client */
    private $client;

    public function __construct(AkamaiClient $client)
    {
        $this->client = $client;
    }

    public function fakeClient(array $expectedResponses): void
    {
        $this->client->fake($expectedResponses);
    }

    public function purgeUrl(string $url): PurgeUrlResponse
    {
        try {
            $response = $this->client->post('/ccu/v3/invalidate/url', [
                'body'    => json_encode([
                    'objects' => [
                        $url,
                    ],
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            return PurgeUrlResponse::bySuccessfullResponse(json_decode((string)$response->getBody()));
        } catch (ClientException $e) {
            return PurgeUrlResponse::byClientExecption($e);
        }
    }
}
