<?php

namespace Kato\Logistics\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use Kato\Logistics\ConfigInterface;
use Kato\Logistics\Utility;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;

abstract class Api implements ApiInterface
{
    /**
     * The Config repository instance.
     *
     * @var \Kato\Logistics\ConfigInterface
     */
    protected $config;

    /**
     * Number of items to return per page.
     *
     * @var null|int
     */
    protected $perPage;

    /**
     * Constructor.
     *
     * @param  \Kato\Logistics\ConfigInterface  $client
     * @return void
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function baseUrl()
    {
        return $this->config->getBaseUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * {@inheritdoc}
     */
    public function setPerPage($perPage)
    {
        $this->perPage = (int) $perPage;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function _get($url = null, $parameters = [])
    {
        if ($perPage = $this->getPerPage()) {
            $parameters['limit'] = $perPage;
        }

        return $this->execute('get', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _head($url = null, array $parameters = [])
    {
        return $this->execute('head', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _delete($url = null, array $parameters = [])
    {
        return $this->execute('delete', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _put($url = null, array $parameters = [])
    {
        return $this->execute('put', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _patch($url = null, array $parameters = [])
    {
        return $this->execute('patch', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _post($url = null, array $parameters = [])
    {
        return $this->execute('post', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _options($url = null, array $parameters = [])
    {
        return $this->execute('options', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($httpMethod, $url, array $parameters = [])
    {
        try {
            if (array_key_exists('json', $parameters)) {
                $response = $this->getClient()->{$httpMethod}('api/'.$url, $parameters);
            } else {
                $parameters = Utility::prepareParameters($parameters);
                $response = $this->getClient()->{$httpMethod}('api/'.$url, [ 'query' => $parameters ]);
            }

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            throw $e;
        }
    }

    /**
     * Returns an Http client instance.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl(), 'handler' => $this->createHandler()
        ]);
    }

    /**
     * Create the client handler.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    protected function createHandler()
    {
        $stack = HandlerStack::create();

        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            $config = $this->config;
            $request = $request->withHeader('Authorization', 'Bearer '.$config->getToken());
            $request = $request->withHeader('Content-Type', 'application/json');
            $request = $request->withHeader('Content-Type', 'application/json');

            return $request;
        }));

        $stack->push(Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null, TransferException $exception = null) {
            return $retries < 3 && ($exception instanceof ConnectException || ($response && $response->getStatusCode() >= 500));
        }, function ($retries) {
            return (int) pow(2, $retries) * 1000;
        }));

        return $stack;
    }
}
