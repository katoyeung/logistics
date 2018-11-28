<?php

namespace Kato\Logistics;

use BernardoSilva\JWTAPIClient\APIClient;
use BernardoSilva\JWTAPIClient\AccessTokenCredentials;
use Illuminate\Support\Facades\Cache;

class LogClient
{
    const VERSION = 'v1';
    const API_LOGIN = '/login';

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
    public function __construct($version, $token, $apiVersion)
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

        $this->config = new Config(self::VERSION, $token, $apiVersion);

        $credentials = new AccessTokenCredentials($token);
        $this->apiClient->setCredentials($credentials);
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
        return Cache::rememberForever('LOG_API_TOKEN', function () use ($type, $query) {
            $this->apiClient = new APIClient($this->baseUrl);
            $options = [
                'verify' => false, // might need this if API uses self signed certificate
                'form_params' => [
                    'key' => $this->username,
                    'password' => $this->password
                ]
            ];

            $response = $this->apiClient->post(self::API_LOGIN, $options);
            $loginResponseDecoded = json_decode($response->getBody()->getContents(), true);

            return $loginResponseDecoded['data']['access_token'];
        });
    }
}
