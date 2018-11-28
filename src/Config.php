<?php

namespace Kato\Logistics;

class Config implements ConfigInterface
{
    /**
     * The current package version.
     *
     * @var string
     */
    protected $version;

    /**
     * The API key.
     *
     * @var string
     */
    protected $token;

    /**
     * The API version.
     *
     * @var string
     */
    protected $apiVersion;

    protected $baseUrl;

    /**
     * Constructor.
     *
     * @param  string  $version
     * @param  string  $token
     * @param  string  $apiVersion
     * @return void
     * @throws \RuntimeException
     */
    public function __construct($version, $token, $apiVersion, $baseUrl)
    {
        $this->setVersion($version);

        $this->setToken($token);

        $this->setApiVersion($apiVersion ?: getenv('LOGISTICS_API_VERSION') ?: 'v1');

        $this->setBaseUrl($baseUrl);

        if (! $this->token) {
            throw new \RuntimeException('The token is not defined!');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

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
}
