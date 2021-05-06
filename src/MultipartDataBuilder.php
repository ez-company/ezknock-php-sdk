<?php

namespace EZKnock;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Http\Discovery\StreamFactoryDiscovery;

class MultipartDataBuilder extends MultipartStreamBuilder {

    public function __construct() {
        parent::__construct(StreamFactoryDiscovery::find());
    }

    public function addResources(array $data) {
        foreach ($data as $name => $value) {
            $this->addResource($name, $value);
        }
    }

    public function addResource($name, $resource, array $options = []) {
        if (is_array($resource)) {
            foreach ($resource as $key => $value) {
                $this->addResource($name.'['.$key.']', $value);
            }
        } else {
            parent::addResource($name, $resource, $options);
        }
    }
}
