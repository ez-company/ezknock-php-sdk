<?php

namespace EZKnock;

class Messenger extends Resource {

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
