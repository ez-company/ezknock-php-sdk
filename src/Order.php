<?php

namespace EZKnock;

class Order extends Resource {

    const PROOF_REVIEW_RESULT_APPROVED = 'approved';
    const PROOF_REVIEW_RESULT_REJECTED_UPLOAD = 'rejected-upload';
    const PROOF_REVIEW_RESULT_REJECTED_MANNER = 'rejected-manner';

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
    public function uploadDocuments($files, array $options = null) {
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

    /**
     * Submit Proof review result
     * @see https://developers.ezknockmarketplace.com/reference#proof-review
     *
     * @param  string $result
     * @return object
     */
    public function proofReview($result) {
        return $this->client->post('/buyers/orders/'.$this->id.'/proof-review', ['result' => $result]);
    }

    /**
     * Submit Diligence review result
     * @see https://developers.ezknockmarketplace.com/reference#diligence-review
     *
     * @param  string $result
     * @return object
     */
    public function diligenceReview($result) {
        return $this->client->post('/buyers/orders/'.$this->id.'/diligence-review', ['result' => $result]);
    }

    /**
     * Set and update deadlines or due dates
     * @see https://developers.ezknockmarketplace.com/reference#set-deadlines
     *
     * @param array $data
     * @return object
     */
    public function setDeadlines(array $data) {
        return $this->client->post('/buyers/orders/'.$this->id.'/deadlines', $data);
    }
}
