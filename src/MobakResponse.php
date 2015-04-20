<?php

namespace Mobak;

class MobakResponse
{
    /**
     * @var array The decoded response from the Mobak XML API
     */
    private $responseData;
    /**
     * @var string The raw response from the Mobak XML API
     */
    private $rawResponse;

    /**
     * @param MobakRequest $request
     * @param array $responseData
     * @param string $rawResponse
     */
    public function __construct(MobakRequest $request, array $responseData, $rawResponse)
    {
        $this->request = $request;
        $this->responseData = $responseData;
        $this->rawResponse = $rawResponse;
    }

    /**
     * Returns the request which produced this response.
     *
     * @return MobakRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the decoded response data.
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->responseData;
    }

    /**
     * Returns the raw response
     *
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function getMobakObject($type = 'Mobak\GraphObject') {
        return (new MobakObject($this->responseData));
    }
}