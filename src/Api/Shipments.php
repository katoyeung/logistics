<?php

namespace Kato\Logistics\Api;

class Shipments extends Api
{
    /**
     * Creates a new order.
     *
     * @param  array  $parameters
     * @return array
     */
    public function create(array $parameters = [])
    {
        return $this->_post('shipments', $parameters);
    }

    /**
     * Retrieves an existing order.
     *
     * @param  string  $shipmentId
     * @return array
     */
    public function find($shipmentId)
    {
        return $this->_get("shipments/{$shipmentId}");
    }

    /**
     * Updates an existing order.
     *
     * @param  string  $shipmentId
     * @param  array  $parameters
     * @return array
     */
    public function update($shipmentId, array $parameters = [])
    {
        return $this->_put("shipments/{$shipmentId}", $parameters);
    }

    /**
     * Returns a list of all the shipments.
     *
     * @param  array  $parameters
     * @return array
     */
    public function all(array $parameters = [])
    {
        return $this->_get('shipments', $parameters);
    }
}
