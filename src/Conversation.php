<?php

namespace EZKnock;

class Conversation extends Resource {

    /**
     * Send message
     * @see https://developers.ezknockmarketplace.com/reference#conversation-send-message
     *
     * @param  string   $body
     * @param  mixed   $files
     * @param  int|null $doctype_id
     * @return object
     */
    public function sendMessage($body, $files = null, int $doctype_id = null) {
        $builder = new MultipartDataBuilder;

        $builder->addResource('body', $body);
        if ($doctype_id) $builder->addResource('doctype_id', $doctype_id);
        if ($files) {
            $files = is_array($files) ? $files : [$files];
            foreach ($files as $file) {
                $builder->addResource('attachments[]', fopen($file, 'r'));
            }
        }

        $stream = $builder->build();
        $boundary = $builder->getBoundary();

        return $this->client->post('/buyers/messenger/'.$this->id.'/conversation', $stream, null, 'multipart/form-data; boundary="'.$boundary.'"');
    }

    /**
     * Mark conversation as seen
     * @see https://developers.ezknockmarketplace.com/reference#conversation-seen
     *
     * @return object
     */
    public function seen() {
        return $this->client->post('/buyers/messenger/'.$this->id.'/conversation/seen');
    }

    /**
     * React to message
     * @see https://developers.ezknockmarketplace.com/reference#conversation-react
     *
     * @param  int    $message_id
     * @param  string $react
     * @return object
     */
    public function react(int $message_id, $react) {
        return $this->client->post('/buyers/messenger/'.$this->id.'/conversation/'.$message_id, [
            'react' => $react
        ]);
    }
}
