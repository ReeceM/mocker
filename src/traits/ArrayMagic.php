<?php

namespace ReeceM\Mocker\Traits;

trait ArrayMagic {
    public function __construct($history = 0)
    {
        $this->lastOffset = $history;
        parent::__construct();
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->lastOffset = $offset;
            $this->container[$offset] = $value;
            return $this->offsetGet($offset);
        }
    }

    public function offsetGet($offset) {
        $this->lastOffset = $offset;
        return isset($this->container[$offset]) ? $this->container[$offset] : $this->offsetSet($offset, new Mocked($this->lastOffset));
    }
}