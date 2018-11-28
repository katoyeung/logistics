<?php

namespace Kato\Logistics;

use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

use Kato\Logistics\Exceptions\InvalidResponseException;
use ReflectionClass;

class ApiClient
{
    const VERSION = '1.0';
    const API_LOGIN = '/api/login';

    protected
        $username = null,
        $password = null,
        $baseUrl = null,
        $apiClient = null,
        $config = null;

    /**
     * Constructor.
     *
     * @param  string  $version
     * @param  string  $token
     * @param  string  $apiVersion
     * @throws \RuntimeException
     */
    public function __construct($apiVersion = null)
    {
        $this->setBaseUrl(getenv('LOGISTICS_API_URL'));
        $this->setUsername(getenv('LOGISTICS_API_USERNAME'));
        $this->setPassword(getenv('LOGISTICS_API_PASSWORD'));

        if (! $this->username || !$this->password) {
            throw new \RuntimeException('The api username or password is not defined!');
        }

        $token = $this->getToken();

        if (! $token) {
            throw new \RuntimeException('The token is not defined!');
        }

        $this->config = new Config(self::VERSION, $token, $apiVersion, $this->baseUrl);
    }

    public static function make($apiVersion = null)
    {
        return new static($apiVersion);
    }

    /**
     * Returns the Config repository instance.
     *
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the Config repository instance.
     *
     * @param  ConfigInterface  $config
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    public function getUsername()
    {
        return $this->config;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->config;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getToken()
    {
        return Cache::rememberForever('LOG_API_TOKEN', function () {
            $client = new Client();
            $response = $client->request('POST', $this->baseUrl . self::API_LOGIN, [
                'form_params' => [
                    'username' => $this->username,
                    'password' => $this->password
                ]
            ]);

            if($response->getStatusCode() == 200) {
                $result = json_decode($response->getBody()->getContents(), true);
                if (isset($result['data']['token'])) {
                    dd($result['data']['token']);
                }else {
                    throw new InvalidResponseException('Logistics-API', 'Login', '');
                }
            }
        });
    }

    /**
     * Dynamically handle missing methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Cartalyst\Stripe\Api\ApiInterface
     */
    public function __call($method, array $parameters)
    {
        if ($this->isIteratorRequest($method)) {
            $apiInstance = $this->getApiInstance(substr($method, 0, -8));

            return (new Pager($apiInstance))->fetch($parameters);
        }

        return $this->getApiInstance($method);
    }

    /**
     * Determines if the request is an iterator request.
     *
     * @return bool
     */
    protected function isIteratorRequest($method)
    {
        return substr($method, -8) === 'Iterator';
    }

    /**
     * Returns the Api class instance for the given method.
     *
     * @param  string  $method
     * @return \Cartalyst\Stripe\Api\ApiInterface
     * @throws \BadMethodCallException
     */
    protected function getApiInstance($method)
    {
        $class = "\\Kato\\Logistics\\Api\\".ucwords($method);

        if (class_exists($class) && ! (new ReflectionClass($class))->isAbstract()) {
            return new $class($this->config);
        }

        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }
}
