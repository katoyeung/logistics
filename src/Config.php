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
    
    /**
     * Constructor.
     *
     * @param  string  $version
     * @param  string  $token
     * @param  string  $apiVersion
     * @return void
     * @throws \RuntimeException
     */
    public function __construct($version, $token, $apiVersion)
    {
        $this->setVersion($version);

        $this->setToken($token);

        $this->setApiVersion($apiVersion ?: getenv('LOGISTICS_API_VERSION') ?: 'v1');

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

    /**
     * Returns the managed account id.
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Sets the managed account id.
     *
     * @param  string  $accountId
     * @return $this
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }
}
