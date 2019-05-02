<?php

namespace ReeceM\Mocker\Utils;

/**
 * Store implementing singleton style
 */
class VarStore {

    /**
     * The memorised list of args
     * @var array $memoized
     */
    protected $memoized = [];

    // Hold the class instance.
    private static $instance = null;
        
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        // no expensive calls unless ??
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function singleton()
    {
      if (self::$instance == null)
      {
        self::$instance = new VarStore();
      }
   
      return self::$instance;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
