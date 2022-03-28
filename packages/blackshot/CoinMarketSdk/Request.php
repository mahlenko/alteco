<?php

namespace Blackshot\CoinMarketSdk;

use Blackshot\CoinMarketSdk\Endpoints\EndpointInterface;
use Blackshot\CoinMarketSdk\Methods\Method;
use Blackshot\CoinMarketSdk\Models\Setting;
use DomainException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Request
{
    private string $server;

    private string $key;

    /**
     * @param string|null $key
     */
    public function __construct(string $key = null)
    {
        $this->key = Setting::getByKey('api_key')->value;

        $this->server = config('coinmarket.is_test')
            ? config('coinmarket.test_server')
            : config('coinmarket.server');
    }

    /**
     * @param Method $method
     * @param EndpointInterface|null $endpoint
     * @return Response
     * @throws GuzzleException
     */
    public function run(Method $method, EndpointInterface $endpoint = null): Response
    {
        $client = new Client();

        try {
            $result = $client->get($this->getUrlMethodRequest($method, $endpoint),
                [
                    'query' => (array) $method,
                    'headers' => [
                        'Accepts' => 'application/json',
                        'X-CMC_PRO_API_KEY' => $this->key
                    ]
                ]
            );
        } catch (RequestException $exception) {
            $content = $exception->getResponse()->getBody()->getContents();

            $content_json = json_decode($content);
            if (json_last_error()) {
                Log::error($content);
                throw new DomainException('CoinMarket SDK response JSON: ' . json_last_error_msg());
            }

            $code = isset($content_json->status) ? $content_json->status->error_code : $content_json->statusCode;
            $description = isset($content_json->status) ? $content_json->status->error_message : $content_json->message;

            return (new Response())->fail($description, $code);
        }

        $data = json_decode($result->getBody()->getContents())->data;

        return (new Response())->ok($data);
    }

    /**
     * @param Method $method
     * @param EndpointInterface|null $endpoint
     * @return string
     */
    private function getUrlMethodRequest(Method $method, EndpointInterface $endpoint = null): string
    {
        $path = Str::replace(__NAMESPACE__ . '\Methods\\', '', get_class($method));
        $path = Str::replace('\\', '/', Str::lower($path));

        return rtrim($this->server, '/') .'/'. $path . $endpoint;
    }

}
