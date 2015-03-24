<?php

namespace Mobak\Http;

/**
 * Class MobakCurl
 * Abstraction for the procedural curl elements so that curl can be mocked
 * and the implementation can be tested.
 * @package Mobak\Http
 */
class MobakCurl
{
    /**
     * @var resource Curl resource instance
     */
    protected $curl = null;

    /**
     * Make a new curl reference instance
     */
    public function init()
    {
        if ($this->curl === null) {
            $this->curl = curl_init();
        }
    }

    /**
     * Set a curl option
     *
     * @param $key
     * @param $value
     */
    public function setOption($key, $value)
    {
        curl_setopt($this->curl, $key, $value);
    }

    /**
     * Set an array of options to a curl resource
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        curl_setopt_array($this->curl, $options);
    }

    /**
     * Send a curl request
     *
     * @return mixed
     */
    public function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * Return the curl error number
     *
     * @return int
     */
    public function errno()
    {
        return curl_errno($this->curl);
    }

    /**
     * Return the curl error message
     *
     * @return string
     */
    public function error()
    {
        return curl_error($this->curl);
    }

    /**
     * Get info from a curl reference
     *
     * @param $type
     *
     * @return mixed
     */
    public function getInfo($type)
    {
        return curl_getinfo($this->curl, $type);
    }

    /**
     * Get the currently installed curl version
     *
     * @return array
     */
    public function version()
    {
        return curl_version();
    }

    /**
     * Close the resource connection to curl
     */
    public function close()
    {
        curl_close($this->curl);
        $this->curl = null;
    }
}