<?php

namespace EZKnock;

class Whoknocked extends Resource {

    /**
     * Get a Seller by ID or username
     * @param  mixed $id ID or username
     * @return [type]     [description]
     */
    public function getSeller($id) {
        return $this->client->get('/whoknocked/sellers/'.$id, null, Seller::class);
    }
}
