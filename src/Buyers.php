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
		return $this->client->post('buyers/orders', $data);
	}

	public function coverage($zipcode) {
		return $this->client->get('buyers/coverage', ['zip' => $zipcode]);
	}
}