<?php

namespace ReeceM\Mocker;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class ReflectionMockery {
    /**
     * The File from the reflection result
     * @var string $file
     */
    private $file;
    /**
     * The reflection class instance
     * @var \ReflectionClass $reflection
     */
    private $reflection;

    /**
     * all the args for the class
     */
    public $args;

    /**
     * Construct the Reflection Mocker Class
     * 
     * @param string|\ReflectionClass $reflect namespace or Reflection
     */
    public function __construct($reflect)
    {
        if($reflect instanceof \ReflectionClass) {
            $reflection = $reflect;
        } else if (is_string($reflect)) {
            try {
                $reflection = new \ReflectionClass($reflect);
            } catch (\Exception $th) {
                throw $th;
            }
        } else {
            throw new \Exception('$reflect not a `string` or instance of \ReflectionClass ¯\_(ツ)_/¯');
        }

        $this->file = (new Filesystem())->get($reflection->getFileName());
        $this->reflection = $reflection;

        $this->extractWantedArgs();
    }
    
    public function newClass(string $name = 'Mocked') : Mocked
    {    
        return new Mocked($name);   
    }
    
    public function extractWantedArgs()
    {
        $params = $this->reflection->getConstructor()->getParameters();
        
        foreach($params as $param) {
            $matches = [];
            $argName = $param->name;
            $result = $this->newClass($argName);
            preg_match_all('/(?<matched>\$' . $argName . '->.*[^;\n])/', $this->file, $matches);
            
            foreach ($matches['matched'] as $key) {
                $name = preg_replace('/(\$' . $argName . '->)/', '', $key);
                $result->$name = $this->newClass($argName);
            }

            Arr::set($this->args, $argName, $result);
        }
    }
    
    public function get($arg = null)
    {
        return Arr::get($this->args, $arg, $this->newClass());
    }

}