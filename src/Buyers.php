<?php

namespace EZKnock;

use Http\Client\Exception;

class Buyers extends Resource {

    /**
     * Creates an order
     *
     * @see https://developers.ezknockmarketplace.com/reference#create-order
     * @param  array $data
     * @return Order
     * @throws Exception
     */
    public function createOrder($data) {
        return $this->client->post('/buyers/orders', $data, Order::class);
    }

    /**
     * Get coverage information
     *
     * @param  string $zipcode
     * @return object
     */
    public function coverage($zipcode) {
        return $this->client->get('/buyers/coverage', ['zip' => $zipcode]);
    }

    /**
     * Get an order
     *
     * @param  string $id
     * @return object
     */
    public function getOrder($id) {
        $id = (int)$id;
        return $this->client->get('/buyers/orders/'.$id, null, Order::class);
    }
}
