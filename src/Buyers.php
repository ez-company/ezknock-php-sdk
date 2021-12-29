<?php

namespace EZKnock;

class Buyers extends Resource {

    /**
     * Hold orders by Zipcodes or Ids
     * @see https://developers.ezknockmarketplace.com/reference#hold-orders
     *
     * @param  array      $order_ids
     * @param  array|null $zipcodes
     * @param  string     $notes
     *
     * @return object
     */
    public function hold(array $order_ids, array $zipcodes = null, $notes = null) {
        return $this->client->post('/buyers/orders/hold', [
            'order_ids' => $order_ids,
            'zipcodes' => $zipcodes,
            'notes' => $notes
        ]);
    }

    /**
     * Creates an order
     * @see https://developers.ezknockmarketplace.com/reference#create-order
     *
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
     * Pay seller with amount
     * @see https://developers.ezknockmarketplace.com/reference#seller-pay
     *
     * @param  int $seller_id
     * @param  float  $amount
     * @param  string $notes
     *
     * @return object
     */
    public function sellerPay($seller_id, float $amount, $notes = null) {
        return $this->client->post('/buyers/sellers/'.$seller_id.'/pay', [
            'amount' => $amount,
            'notes' => $notes
        ]);
    }

    /**
     * Add funds to wallet from funding source
     *
     * @param  string $source_id
     * @param  float  $amount
     * @param  strinng $notes
     *
     * @return object
     */
    public function fundWallet($source_id, float $amount, $notes = null) {
        return $this->client->post('/buyers/wallet/fund', [
            'amount' => $amount,
            'notes' => $notes,
            'srcid' => $source_id
        ]);
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
