<?php

namespace ReeceM\Mocker\Traits;

trait ArrayMagic
{

    /**
     * Returns the iterator for loops
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->store->memoized);
    }

    /**
     * sets a value in the array object using the offset
     * @param mixed $offset key of the array
     * @param mixed $value the value to insert at offset
     * @return void|self
     */
    public function offsetSet($offset, $value)
    {

        if (is_null($offset)) {
            $this->setMockeryVariables($offset, $value);
        } else {
            $this->setMockeryVariables($offset, $value);
            return $this->offsetGet($offset);
        }
    }

    /**
     * This gets the value at the offset, if it is not set it will set and return a new instance
     *
     * @param mixed $offset the offset to get
     * @return self
     */
    public function offsetGet($offset)
    {

        return isset($this->store->memoized[$offset]) ?
            $this->store->memoized[$offset] :
            $this->offsetSet($offset, new self($this->arrayTrace(), $this->store, $this->trace));
    }

    /**
     * checks if the key exists in the array
     *  @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->store->memoized[$offset]);
    }
    /**
     * unsets a key in array/object
     *  @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->store->memoized[$offset]);
    }

    private function arrayTrace(): array
    {
        // one up...
        $selfTrace = debug_backtrace(false, 2);
        return array([
            'function'  => $selfTrace[1]['function'] == 'offsetGet' ? '__get' : '__set',
            'args'      => $selfTrace[1]['args'] ?? [null],
            'type'      => $selfTrace[1]['type'] ?? '->'
        ]);
    }
}
