<?php

namespace EZKnock;

class Options extends Resource {

    /**
     * Get list of qualifications
     * @see https://developers.ezknockmarketplace.com/reference#qualifications
     *
     * @return array
     */
    public function getQualifications() {
        return $this->client->get('/options/qualifications');
    }
}
