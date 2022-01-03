<?php

namespace EZKnock;

class Order extends Resource {

    /**
     * Relese buyer hold
     * @see https://developers.ezknockmarketplace.com/reference#unhold-order
     *
     * @return object
     */
    public function unhold() {
        return $this->client->post('/buyers/orders/'.$this->id.'/unhold');
    }

    /**
     * Put on Buyer hold
     * @see https://developers.ezknockmarketplace.com/reference#hold-order
     *
     * @return object
     */
    public function hold() {
        return $this->client->post('/buyers/orders/'.$this->id.'/hold');
    }

    /**
     * Upload documents
     * @see https://developers.ezknockmarketplace.com/reference#upload-order-documents
     *
     * @param  mixed $files
     * @param  array  $options
     *
     * @return object
     */
    public function uploadDocuments($files, $options = []) {
        $builder = new MultipartDataBuilder;
        if ($options) $builder->addResources($options);

        $files = is_array($files) ? $files : [$files];
        foreach ($files as $file) {
            $builder->addResource('files[]', fopen($file, 'r'));
        }

        $stream = $builder->build();
        $boundary = $builder->getBoundary();

        return $this->client->post('/buyers/orders/'.$this->id.'/documents', $stream, null, 'multipart/form-data; boundary="'.$boundary.'"');
    }

    /**
     * Get documents
     * @see https://developers.ezknockmarketplace.com/reference#get-order-documents
     *
     * @param  mixed $type
     * @return array
     */
    public function getDocuments($type = null) {
        return $this->client->get('/buyers/orders/'.$this->id.'/documents', ['type' => $type]);
    }
}
