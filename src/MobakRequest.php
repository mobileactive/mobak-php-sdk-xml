<?php
namespace Mobak;

use Mobak\Http\MobakHttpable;
use Mobak\Http\MobakCurlHttpClient;

class MobakRequest
{
    const VERSION = '1.0.0';

    const BASE_URL = 'http://client.mobak.ru/api';

    /**
     * @var MobakUser The session used for this request
     */
    private $user;

    /**
     * @var string The HTTP method for the request
     */
    private $method;
    /**
     * @var string The path for the request
     */
    private $path;
    /**
     * @var array The parameters for the request
     */
    private $params;

    /**
     * @var \Mobak\Http\MobakHttpable HTTP client handler
     */
    private static $httpClientHandler;

    /**
     * @return MobakUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * getPath - Returns the associated path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * getParameters - Returns the associated parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * getMethod - Returns the associated method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * setHttpClientHandler - Returns an instance of the HTTP client
     * handler
     *
     * @param \Mobak\Http\MobakHttpable
     */
    public static function setHttpClientHandler(MobakHttpable $handler)
    {
        static::$httpClientHandler = $handler;
    }

    /**
     * getHttpClientHandler - Returns an instance of the HTTP client
     * data handler
     *
     * @return MobakHttpable
     * @throws MobakBaseException
     */
    public static function getHttpClientHandler()
    {
        if (static::$httpClientHandler) {
            return static::$httpClientHandler;
        }
        if (function_exists('curl_init')) {
            return new MobakCurlHttpClient();
        }

        throw new MobakRequestException("Curl does not exist");
    }

    /**
     * @param MobakUser $user
     * @param $method
     * @param $path
     * @param null $parameters
     */
    public function __construct(MobakUser $user, $method, $path, $parameters = null)
    {
        $this->user = $user;
        $this->method = $method;
        $this->path = $path;
        $params = ($parameters ?: array());
        $this->params = $params;
    }

    /**
     * Returns the base Graph URL.
     *
     * @return string
     */
    protected function getRequestURL()
    {
        $baseUrl = static::BASE_URL;
        return $baseUrl . '/' . $this->path;
    }

    /**
     * execute - Makes the request to Facebook and returns the result.
     *
     * @return MobakResponse
     *
     * @throws MobakBaseException
     * @throws MobakRequestException
     */
    public function execute()
    {
        $url = $this->getRequestURL();
        $params = $this->getParameters();
        if ($this->method === 'GET') {
            $url = self::appendParamsToUrl($url, $params);
            $params = array();
        }
        $connection = self::getHttpClientHandler();
        $connection->addRequestHeader('User-Agent', 'mobak-php-' . self::VERSION);
        $connection->addRequestHeader('Accept-Encoding', '*');

        $result = $connection->send($url, $this->method, $params);

        $decodedResult = simplexml_load_string($result);

        if ($decodedResult === false) {
            throw new MobakBaseException("Invalid response");
        }

        if ($decodedResult->information[0]->attributes['code'] !== 0) {
            throw new MobakRequestException($result, $decodedResult, $connection->getResponseHttpStatusCode());
        }

        return new MobakResponse($this, $decodedResult, $result);
    }

    /**
     * Generate and return the appsecret_proof value for an access_token
     *
     * @param string $token
     *
     * @return string
     */
    public function getAppSecretProof($token)
    {
        return hash_hmac('sha256', $token, FacebookSession::_getTargetAppSecret());
    }

    /**
     * appendParamsToUrl - Gracefully appends params to the URL.
     *
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public static function appendParamsToUrl($url, array $params = array())
    {
        if (!$params) {
            return $url;
        }
        if (strpos($url, '?') === false) {
            return $url . '?' . http_build_query($params, null, '&');
        }
        list($path, $query_string) = explode('?', $url, 2);
        parse_str($query_string, $query_array);
        // Favor params from the original URL over $params
        $params = array_merge($params, $query_array);
        return $path . '?' . http_build_query($params, null, '&');
    }

}