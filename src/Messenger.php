<?php

namespace EZKnock;

class Messenger extends Resource {

    /**
     * Creates a new Conversation instance
     *
     * @param  int    $channel_id
     * @return Conversation
     */
    public function conversations(int $channel_id) {
        return new Conversation($this->client, ['id' => $channel_id]);
    }

    /**
     * Get channel conversation
     * @see https://developers.ezknockmarketplace.com/reference#get-conversation
     *
     * @param
     * @return object
     */
    public function getConversation(int $channel_id, array $options = null) {
        return $this->client->get('/buyers/messenger/'.$channel_id.'/conversation', $options, Conversation::class);
    }
}
