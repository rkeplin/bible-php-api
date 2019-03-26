<?php
namespace Core;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2012 Rob Keplin.
 * @license TBD
 **/
class Autoload
{
    /**
     * @var array
     **/
    private $_paths = array();

    /**
     * Takes an array of paths to autoload.
     *
     * @param array $paths
     * @return void
     **/
    public function __construct(array $paths)
    {
        $this->_paths = $paths;
        spl_autoload_register(array($this, 'doLoad'));
    }

    /**
     * Requires the class, if it exists.
     *
     * @param string $class
     * @return void
     **/
    private function doLoad($class)
    {
        foreach($this->_paths as $path) {
            $file = str_replace('\\', '/', $class) . '.php';

            if(file_exists($path . $file)) {
                require_once $path . $file;
                break;
            }
        }
    }
}
