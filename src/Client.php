<?php

namespace ShareCloth\Look\Api;


use GuzzleHttp\Psr7\Response;
use ShareCloth\Look\Api\Exception\BadResponseException;
use ShareCloth\Look\Api\Response\ApiResponse;
use ShareCloth\Look\Api\Response\JsonResponseFactory;

class Client implements ClientInterface
{

    /** @var  \GuzzleHttp\Client */
    protected $httpClient;

    /** @var  string */
    protected $apiSecret;

    /** @var  string */
    protected $client;

    /** @var string */
    protected $baseUri = 'http://api.sharecloth.com/v1/';

    /** @var  integer */
    protected $timeout = 300;

    /**
     * Client constructor.
     * @param $apiSecret
     * @param array $httpClientConfig
     *
     */
    public function __construct($apiSecret, $httpClientConfig = [])
    {
        $this->initHttpClient($httpClientConfig);
        $this->apiSecret = $apiSecret;
    }


    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function itemsList($options)
    {
        return $this->runApiMethod('items/list', $options);
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function avatarList($options = [])
    {
        return $this->runApiMethod('avatar/list', $options);
    }

    /**
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionsCollection()
    {
        return $this->runApiMethod('options/collections');
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionsCollectionSizes($options)
    {
        return $this->runApiMethod('options/collection_sizes', $options);
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionsAddSizingCollection($options)
    {
        return $this->runApiMethod('options/add-sizing-collection', $options);

    }

    /**
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionTypes()
    {
        return $this->runApiMethod('options/types');
    }

    /**
     * @param $options
     * @return array
     * @throws BadResponseException
     */
    public function optionsAddCollection($options)
    {
        return $this->runApiMethod('options/add-collection', $options);
    }


    /**
     * @param $options
     * @return array
     * @throws BadResponseException
     */
    public function optionsSizes($options)
    {
        return $this->runApiMethod('options/sizes', $options);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarCreate($options = [])
    {
        return $this->runApiMethod('avatar/create', $options);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarUpdate($options = [])
    {
        return $this->runApiMethod('avatar/update', $options);
    }

    /**
     * Basic method for all api calls
     * @param $method
     * @param array $options
     * @return mixed
     * @throws BadResponseException
     */
    protected function runApiMethod($method, $options = [])
    {
        $response = $this->makeRequest($method, $options);
        return $response->getData();
    }



    /**
     * @param $uri
     * @param $options
     * @param string $method
     * @return ApiResponse
     * @throws BadResponseException
     */
    protected function makeRequest($uri, $options, $method = 'POST')
    {
        if ($this->apiSecret) {
            $options = array_merge($options, ['api_secret' => $this->apiSecret]);
        }

        $response = $this->httpClient->request($method, $uri, ['form_params' => $options]);
        if ($response->getStatusCode() == 200) {
            $parsed =  $this->parseResponse($response);
            if (! $parsed->isResponseSuccess() ) {
                throw new BadResponseException($parsed->getErrorMessage());
            }

            return $parsed;
        }

        throw new BadResponseException($response->getStatusCode());
    }

    /**
     * @param $httpClientConfig
     */
    protected function initHttpClient($httpClientConfig)
    {
        $config = array_merge([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
        ], $httpClientConfig);

        $this->httpClient = new \GuzzleHttp\Client($config);
    }

    /**
     * Parse response from API and returns response object
     * @param Response $response
     * @return ApiResponse
     */
    protected function parseResponse(Response $response)
    {
        $data = $response->getBody()->getContents();
        $factory = new JsonResponseFactory();
        return $factory->getApiResponse($data);
    }


}