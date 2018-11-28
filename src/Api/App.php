<?php

namespace Kato\Logistics\Api;

class App extends Api
{
    /**
     * Retrieves the details of the account, based on the
     * API key that was used to make the request.
     *
     * @return array
     */
    public function details()
    {
        return $this->_get('app');
    }

    /**
     * Creates a new account.
     *
     * @param  array  $parameters
     * @return array
     */
    public function create(array $parameters = [])
    {
        return $this->_post('apps', $parameters);
    }

    /**
     * Retrieves an existing account.
     *
     * @param  string  $appId
     * @return array
     */
    public function find($appId)
    {
        return $this->_get("apps/{$appId}");
    }

    /**
     * Updates an existing account.
     *
     * @param  string  $appId
     * @param  array  $parameters
     * @return array
     */
    public function update($appId, array $parameters = [])
    {
        return $this->_post("apps/{$appId}", $parameters);
    }

    /**
     * Deletes an existing account.
     *
     * @param  string  $appId
     * @return array
     */
    public function delete($appId)
    {
        return $this->_delete("apps/{$appId}");
    }

    /**
     * Rejects an existing account.
     *
     * @param  string  $appId
     * @param  string  $reason
     * @return array
     */
    public function reject($appId, $reason)
    {
        return $this->_post("apps/{$appId}/reject", compact('reason'));
    }

    /**
     * Updates an existing account.
     *
     * @param  string  $appId
     * @param  string  $file
     * @param  array  $parameters
     * @return array
     */
    public function verify($appId, $file, $purpose)
    {
        $upload = (new FileUploads($this->config))->create(
            $file, $purpose, [ 'Stripe-Account' => $appId ]
        );

        $this->update($appId, [
            'legal_entity' => [
                'verification' => [
                    'document' => $upload['id'],
                ],
            ],
        ]);

        return $this->_get('apps/'.$appId);
    }

    /**
     * Returns a list of all the connected apps.
     *
     * @param  array  $parameters
     * @return array
     */
    public function all(array $parameters = [])
    {
        return $this->_get('apps', $parameters);
    }

    /**
     * Creates a login link.
     *
     * @param  string  $appId
     * @param  string|null  $redirectUrl
     * @return array
     */
    public function createLoginLink($appId, $redirectUrl = null)
    {
        return $this->_post("apps/{$appId}/login_links", [
            'redirect_url' => $redirectUrl,
        ]);
    }
}
