<?php

namespace Kato\Logistics\Api;

class Deliveries extends Api
{
    /**
     * Creates a new order.
     *
     * @param  array  $parameters
     * @return array
     */
    public function create(array $parameters = [])
    {
        return $this->_post('deliveries', $parameters);
    }

    /**
     * Retrieves an existing order.
     *
     * @param  string  $deliveryId
     * @return array
     */
    public function find($deliveryId)
    {
        return $this->_get("deliveries/{$deliveryId}");
    }

    /**
     * Updates an existing order.
     *
     * @param  string  $deliveryId
     * @param  array  $parameters
     * @return array
     */
    public function update($deliveryId, array $parameters = [])
    {
        return $this->_post("deliveries/{$deliveryId}", $parameters);
    }

    /**
     * Returns a list of all the deliveries.
     *
     * @param  array  $parameters
     * @return array
     */
    public function all(array $parameters = [])
    {
        return $this->_get('deliveries', $parameters);
    }
}
