<?php

namespace EZKnock;

use Psr\Http\Message\ResponseInterface;

abstract class Resource {
    /**
     * @var Client
     */
    protected $client;

    /**
     * Resource constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client, $data = null) {
        $this->client = $client;

        if ($data) {
            if ($data instanceof ResponseInterface) {
                $stream = $data->getBody()->getContents();
                $this->_body = json_decode($stream);
            } else {
                $this->_body = $data;
            }

            $this->buildProperties();
        }
    }

    /**
     * Populate the context properties
     */
    private function buildProperties() {
        if (!$this->_body) return;

        foreach ($this->_body as $property => $value) {
            if (is_string($property) === false) continue;
            $this->{$property} = $value;
        }
    }
}
