<?php

namespace ReeceM\Mocker;

use Illuminate\Support\Arr;
use ReeceM\Mocker\Utils\VarStore;
use ReeceM\Mocker\Traits\ArrayMagic;
use ReeceM\Mocker\Traits\ObjectMagic;

/**
 * the default mocked class that has dynamic variable names and setting of them too
 * this is what makes tha fake objects that result from reading un-typed params
 */
class Mocked extends \ArrayObject
{

    /**
     *
     * Array related methods
     */
    use ArrayMagic, ObjectMagic;
    /**
     * the base array from the new call
     */
    private $base;

    /**
     * the previous calls to the method
     */
    private $previous;

    /**
     * vars set
     * @var array $vars
     */
    private $vars;

    /**
     * Singleton storage
     * @var \ReeceM\Mocker\Utils\VarStore $store
     */
    private $store;

    /**
     * the combined class list to dump for to string
     * @var array $trace
     */
    private $trace = [];

    private static $GET_METHOD = "__get";
    private static $SET_METHOD = "__set";

    /*
     * The mocked constructor
     * @param string|array $base the name of the arg/object (buttery biscuit base)
     * @param \ReeceM\Mocker\Utils\VarStore $store singleton variable storage
     * @param mixed $previous the base of the calling class
     */
    public function __construct($base, VarStore $store, $previous = [])
    {
        $this->previous = $previous;
        $this->store    = $store;
        $this->base     = $base;

        if (is_string($base)) {
            $this->base     = [['args' => [$base], 'function' => static::$GET_METHOD]];
        }

        $this->structureMockeryCalls();
    }

    /**
     * takes the debug trace and structures the single level call
     * @todo ensure to implement a call limit on this...
     */
    private function structureMockeryCalls()
    {
        $toSet = null;
        try {
            $args = Arr::get($this->base[0], 'args', []); // only one if its a get command
            $function = Arr::get($this->base[0], 'function', '__get');
            // $type = Arr::get($this->base[0], 'type', ''); '->' / '::'
            if ($function == self::$GET_METHOD) {
                // merge the preceding calls with this one
                array_push($this->previous, $args[0]);
                $this->trace = $this->previous;
            } elseif ($function == self::$SET_METHOD) {
                array_push($this->previous, $args[0]);
                $this->trace = $this->previous;
                $toSet = $args[1];
            }

            return $this->setMockeryVariables($args[0], $toSet);
        } catch (\Exception $th) {
            throw $th;
        }
    }

    private function setMockeryVariables($key, $value = null)
    {
        $memorable = $this->store->memoized;

        $memorable[$key] = $value;

        if ($value instanceof self) {
            $this->store->memoized = array_merge($this->store->memoized, $memorable);
        }

        $this->store->memoized = array_merge($memorable, $this->store->memoized);
    }

    /**
     * Return a string of the called object
     * would be at the end of the whole thing
     * @param void
     * @return string
     */
    public function __toString()
    {
        $calledValue = $this->store->memoized[array_reverse($this->trace)[0]] ?? null;

        if ($calledValue != null) {
            return implode("->", $this->trace) . ' => ' . collect($calledValue);
        }

        return implode("->", $this->trace);
    }

    public function __getStore()
    {
        return $this->store->memoized;
    }
}
