<?php 

namespace ReeceM\Mocker;

use Illuminate\Support\Arr;

/**
 * the default mocked class that has dynamic variable names and setting of them too
 * this is what makes tha fake objects that result from reading un-typed params
 */
class Mocked {

    /**
     * the base array from the new call
     */
    private $base;

    /**
     * the previous calls to the method
     */
    private $previous;

    /**
     * previous vars set
     */
    private $vars = [];

    /**
     * the combined class list to dump for to string
     * @var array $trace
     */
    private $trace = [];

    /**
     * 
     * @param string|array $base the name of the arg/object (buttery biscuit base)
     * @param mixed $previous the base of the calling class
     */ 
    public function __construct($base, $previous = [], $vars = [])
    {
        $this->base     = $base;
        $this->previous = $previous;
        $this->vars     = $vars;

        if(is_array($this->base)) {
            $this->structureCalls();
        }
    }

    /**
     * takes the debug trace and structures the single level call
     * @todo ensure to implement a call limit on this...
     */
    private function structureCalls()
    {
        try {
            $args = Arr::get($this->base[0], 'args', [])[0]; // only one if its a get command
            // $function = Arr::get($this->base[0], 'function', '');
            // $type = Arr::get($this->base[0], 'type', '');
            
            // merge the preceding calls with this one
            array_push($this->previous, $args);
            
            $this->trace = $this->previous;
            
            $this->vars = array_fill_keys($this->trace, null);

        } catch (\Exception $th) {
            throw $th;
        }
    }

    public function __get($name)
    {
        return new Mocked(debug_backtrace(false, 1), $this->trace, $this->vars);
    }

    public function __call($name, $arguments)
    {
        // return new Mocked()
    }

    /**
     * set the value of something inside the class
     */
    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * Return a string of the called object
     * would be at the end of the whole thing
     */
    public function __toString()
    {
        // dump($this->vars);
        
        // $calledValue = Arr::get($this->vars, implode(".", $this->trace));

        // if($calledValue != null) {
        //     return implode("->", $this->trace) . ' => ' . $calledValue;
        // }
        return implode("->", $this->trace);
    }
}