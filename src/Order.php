<?php

namespace EZKnock;

class Order extends Resource {

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
}
