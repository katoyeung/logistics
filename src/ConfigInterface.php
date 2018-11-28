<?php

namespace Kato\Logistics;

interface ConfigInterface
{
    /**
     * Returns the current package version.
     *
     * @return string
     */
    public function getVersion();

    /**
     * Sets the current package version.
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Returns the API key.
     *
     * @return string
     */
    public function getToken();

    /**
     * Sets the API key.
     *
     * @param  string  $apiKey
     * @return $this
     */
    public function setToken($token);

    /**
     * Returns the API version.
     *
     * @return string
     */
    public function getApiVersion();

    /**
     * Sets the API version.
     *
     * @param  string  $apiVersion
     * @return $this
     */
    public function setApiVersion($apiVersion);
}
