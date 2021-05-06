<?php

namespace EZKnock;

class Buyers extends Resource {

    /**
     * Creates an order
     *
     * @see https://developers.ezknockmarketplace.com/reference#create-order
     * @param  array $data
     * @return Order
     */
    public function createOrder(array $data, $type = Order::TYPE_CBO) {
        $builder = new MultipartDataBuilder;
        $builder->addResources($data);
        $builder->addResource('type', $type);

        $stream = $builder->build();
        $boundary = $builder->getBoundary();

        return $this->client->post('/buyers/orders', $stream, Order::class, 'multipart/form-data; boundary="'.$boundary.'"');
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
