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
        return $this->_post("shipments/{$shipmentId}", $parameters);
    }

    /**
     * Pays the given order.
     *
     * @param  string  $shipmentId
     * @param  array  $parameters
     * @return array
     */
    public function pay($shipmentId, array $parameters = [])
    {
        return $this->_post("shipments/{$shipmentId}/pay", $parameters);
    }

    /**
     * Returns the given order.
     *
     * @param  string  $shipmentId
     * @param  array  $items
     * @return array
     */
    public function returnItems($shipmentId, array $items = [])
    {
        return $this->_post("shipments/{$shipmentId}/returns", compact('items'));
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
