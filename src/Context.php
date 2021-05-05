<?php

namespace EZKnock;

use Psr\Http\Message\ResponseInterface;

abstract class Context {

    /**
     * @var object
     */
    private $_body;

    public function __construct($response) {
        if ($response instanceof ResponseInterface) {
            $stream = $response->getBody()->getContents();
            $this->_body = json_decode($stream);
        } else {
            $this->_body = $response;
        }

        $this->buildProperties();
    }

    /**
     * Populate the context properties
     */
    private function buildProperties() {
        foreach ($this->_body as $property => $value) {
            if (is_string($property) === false) continue;
            $this->{$property} = $value;
        }
    }
}
