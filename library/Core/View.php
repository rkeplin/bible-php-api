<?php
namespace Core;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2012 Rob Keplin.
 * @license TBD
 **/
class View
{
    /**
     * @var array
     **/
    protected $_vars;

    /**
     * @var string
     **/
    protected $_path;

    /**
     * @var string
     **/
    protected $_layout_script;

    /**
     * @var boolean
     **/
    protected $_layout_enabled;

    /**
     * @var string
     **/
    protected $_layout_content;

    /**
     * Set the path to the view folder
     * when creating a view object.
     *
     * @param string $path
     * @return void
     **/
    public function __construct($path)
    {
        $this->_path = $path;
        $this->_layout_script = 'layout.php';
        $this->_layout_enabled = false;
    }

    /**
     * Set the vars that the view will
     * have access to.
     *
     * @param array $vars
     * @return void
     **/
    public function setVars($vars)
    {
        $this->_vars = $vars;
    }

    /**
     * Render a script in the view folder.
     *
     * @param string $script
     * @return void
     **/
    public function render($script = null)
    {
        if(file_exists($this->_path . $script)) {
            ob_start();
            include $this->_path . $script;
            $this->_layout_content = ob_get_contents();
            ob_end_clean();

            if($this->_layout_enabled) {
                include $this->_path . $this->_layout_script;
            } else {
                echo $this->_layout_content;
            }
        }
    }

    /**
     * Include a view script file.
     *
     * @param string $script
     * @return void
     **/
    public function partial($script = null)
    {
        if(file_exists($this->_path . $script)) {
            include $this->_path . $script;
        }
    }

    /**
     * Sets the layout script.
     *
     * @param string $script
     * @return void
     **/
    public function setLayoutScript($script)
    {
        $this->_layout_script = $script;
    }

    /**
     * Disables rendering of layout.
     *
     * @param void
     * @return void
     **/
    public function disableLayout()
    {
        $this->_layout_enabled = false;
    }

    /**
     * Enables rendering of layout.
     *
     * @param void
     * @return void
     **/
    public function enableLayout()
    {
        $this->_layout_enabled = true;
    }

    /**
     * Magically gets a view variable.
     *
     * @param string $key
     * @return mixed
     **/
    public function __get($key)
    {
        return $this->_vars[$key];
    }

    /**
     * Magically set a view variable.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     **/
    public function __set($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    /**
     * See if a variable is set.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->_vars[$key]);
    }

    /**
     * Unset a view variable.
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {

    }
}
