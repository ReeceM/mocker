<?php 

namespace ReeceM\Mocker;

use Illuminate\Support\Arr;

/**
 * the default mocked class that has dynamic variable names and setting of them too
 * this is what makes tha fake objects that result from reading un-typed params
 */
class Mocked {
    private $basename;
    private $vars = [];
        
    public function __construct($_basename)
    {
        $this->basename = $_basename;
    }

    public function __get($name)
    {
        return Arr::get($this->vars, $name, $this);

        // return __(implode('.', [
        //                 'mocked',
        //                 $this->basename,
        //                 $name
        //     ]));
    }

    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
        // return $name . ' ' . $value;
    }

    public function __toString()
    {
        return 'mocked ' . $this->basename;
        /**
         * @todo make this return a translation instance for the basename
         * allows user define-able test vars
         */
        return __('mocked.' . $this->basename . '_to_string');
    }
}