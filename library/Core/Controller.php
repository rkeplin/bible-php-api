<?php
namespace Core;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2012 Rob Keplin.
 * @license TBD
 **/
abstract class Controller
{
    /**
     * @var array
     **/
    protected $_post_data;

    /**
     * @var array
     **/
    protected $_get_data;

    /**
     * @var array
     */
    protected $_route_params;

    /**
     * Redirect the request to the new location
     * and exit.
     *
     * @param string $location
     * @return void
     **/
    protected function _relocate($location)
    {
        header('Location: ' . $location);
        exit;
    }

    /**
     * Sets the route params
     *
     * @return array
     */
    public function getRouteParams()
    {
        return $this->_route_params;
    }

    /**
     * Gets the route params
     *
     * @param array $route_params
     */
    public function setRouteParams($route_params)
    {
        $this->_route_params = $route_params;
    }

    /**
     * Get a route param value.
     *
     * @param string $key
     * @return string|array
     **/
    protected function _getRouteParam($key = null)
    {
        if(!$key) {
            return $this->_route_params;
        }

        return $this->_route_params[$key];
    }

    /**
     * @param null $key
     * @return bool
     */
    protected function _hasRouteParam($key = null)
    {
        return isset($this->_route_params[$key]);
    }

    /**
     * Set post values.
     *
     * @param array $data
     * @return void
     **/
    public function setPost(array $data)
    {
        $this->_post_data = $data;
    }

    /**
     * Set parameter values.
     *
     * @param array $data
     * @return void.
     **/
    public function setParams(array $data)
    {
        $this->_get_data = $data;
    }

    /**
     * Get a post value.
     *
     * @param string $key
     * @return string|array
     **/
    protected function _getPost($key = null)
    {
        if(!$key) {
            return $this->_post_data;
        }

        return $this->_post_data[$key];
    }

    /**
     * Get a param value.
     *
     * @param string $key
     * @return string|array
     **/
    protected function _getParam($key = null)
    {
        if(!$key) {
            return $this->_get_data;
        }

        return $this->_get_data[$key];
    }

    /**
     * Checks if a post value is set.
     *
     * @param string $key
     * @return boolean
     **/
    protected function _hasPost($key = null)
    {
        if(null != $key) {
            return isset($this->_post_data[$key]);
        }

        return (count($this->_post_data) > 0);
    }

    /**
     * Checks if a param value is set.
     *
     * @param string $key
     * @return boolean
     **/
    protected function _hasParam($key = null)
    {
        if(null != $key) {
            return isset($this->_get_data[$key]);
        }

        return (count($this->_get_data) > 0);
    }

}
