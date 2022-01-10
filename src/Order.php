<?php

namespace EZKnock;

class Order extends Resource {

    const PROOF_REVIEW_RESULT_APPROVED = 'approved';
    const PROOF_REVIEW_RESULT_REJECTED_UPLOAD = 'rejected-upload';
    const PROOF_REVIEW_RESULT_REJECTED_MANNER = 'rejected-manner';

    const RECIPIENT_PRIMARY = 'primary';
    const RECIPIENT_LEFT_WITH = 'left-with';

    const RETURN_TYPE_DEFAULT = 'default';
    const RETURN_TYPE_BUYER_UPLOAD = 'buyer-upload';
    const RETURN_TYPE_BUYER_POST_UPLOAD = 'buyer-post-upload';
    const RETURN_TYPE_SELLER_UPLOAD = 'seller-upload';

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
     * Process partial review
     * @see https://developers.ezknockmarketplace.com/reference#partial-review
     *
     * @param  string        $result
     * @param  string        $notes
     * @param  float|integer $amount
     * @return object
     */
    public function partialReview($result, $notes = null, float $amount = 0) {
        return $this->client->post('/buyers/orders/'.$this->id.'/partial-review', [
            'result' => $result,
            'notes' => $notes,
            'amount' => $amount
        ]);
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

    /**
     * Get recipients (primary | left-with)
     * @see https://developers.ezknockmarketplace.com/reference#get-recipients
     *
     * @param  string $type
     * @return array
     */
    public function getRecipients($type = self::RECIPIENT_PRIMARY) {
        return $this->client->get('/buyers/orders/'.$this->id.'/recipients', [
            'type' => $type
        ]);
    }

    /**
     * Update a recipient
     * @see https://developers.ezknockmarketplace.com/reference#update-recipient
     *
     * @param  int    $id
     * @param  array  $data
     * @return object
     */
    public function updateRecipient(int $id, array $data) {
        return $this->client->post('/buyers/orders/'.$this->id.'/recipients/'.$id, $data);
    }

    /**
     * Get instructions
     * @see https://developers.ezknockmarketplace.com/reference#get-instructions
     *
     * @param  string $type
     * @return array
     */
    public function getInstructions($type = null) {
        return $this->client->get('/buyers/orders/'.$this->id.'/instructions', [
            'type' => $type
        ]);
    }

    /**
     * Update an instruction
     * @see https://developers.ezknockmarketplace.com/reference#update-instruction
     *
     * @param  int    $id
     * @param  array  $data
     * @return object
     */
    public function updateInstruction(int $id, $body) {
        return $this->client->post('/buyers/orders/'.$this->id.'/instructions/'.$id, [
            'body' => $body
        ]);
    }

    /**
     * Update general information
     * @see https://developers.ezknockmarketplace.com/reference#update-general-info
     *
     * @param  array $data
     * @return object
     */
    public function updateGeneralInfo(array $data) {
        return $this->client->post('/buyers/orders/'.$this->id.'/general-info', $data);
    }

    /**
     * Update delivery address
     * @see https://developers.ezknockmarketplace.com/reference#update-delivery-address
     *
     * @param  array  $data
     * @return object
     */
    public function updateAddress(array $data) {
        return $this->client->post('/buyers/orders/'.$this->id.'/address', $data);
    }

    /**
     * Cancel the Order
     * @see https://developers.ezknockmarketplace.com/reference#cancel-order
     *
     * @param  string $notes
     * @return object
     */
    public function cancel($notes) {
        return $this->client->post('/buyers/orders/'.$this->id.'/cancel', [
            'notes' => $notes
        ]);
    }

    /**
     * Pull Order from seller
     * @see https://developers.ezknockmarketplace.com/reference#pull-order
     *
     * @param  string $notes
     * @param  float  $amount
     * @return object
     */
    public function pull($notes, float $amount = 0) {
        return $this->client->post('/buyers/orders/'.$this->id.'/pull', [
            'notes' => $notes,
            'amount' => $amount
        ]);
    }

    /**
     * Rate a seller
     * @see https://developers.ezknockmarketplace.com/reference#seller-rating
     *
     * @param  int $seller_id
     * @param  array $data
     * @return object
     */
    public function sellerRating($data) {
        return $this->client->post('/buyers/orders/'.$this->id.'/seller-rating', $data);
    }
}
