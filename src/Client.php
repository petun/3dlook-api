<?php

namespace ShareCloth\Look\Api;


use GuzzleHttp\Psr7\Response;
use ShareCloth\Look\Api\Exception\BadResponseException;
use ShareCloth\Look\Api\Response\ApiResponse;
use ShareCloth\Look\Api\Response\JsonResponseFactory;

class Client implements ClientInterface
{

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /** @var  \GuzzleHttp\Client */
    protected $httpClient;

    /** @var  string */
    protected $apiKey;

    /** @var  string */
    protected $client;

    /** @var string */
    protected $baseUri = 'http://saia.3dlook.me/api/v1/';

    /** @var  integer */
    protected $timeout = 300;

    /**
     * Client constructor.
     * @param $apiKey
     * @param array $httpClientConfig
     *
     */
    public function __construct($apiKey, $httpClientConfig = [])
    {
        $this->initHttpClient($httpClientConfig);
        $this->apiKey = $apiKey;
    }


    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function personCustomBody($options)
    {
        return $this->runApiMethod('person/custom-body-3db2b', $options, self::METHOD_POST);
    }

    /**
     * @param $pathToFile
     * @return mixed
     */
    public function uploads($pathToFile)
    {
        return $this->runApiMethod('uploads/', [], self::METHOD_POST, [
            [
                'name' => 'image',
                'contents' => fopen($pathToFile, 'r')
            ]
        ]);
    }


    /**
     * @param $options
     * @return mixed
     */
    public function step($options)
    {
        return $this->runApiMethod('step/', $options, self::METHOD_POST);
    }

    /**
     * @param $options
     * @return mixed
     */
    public function complete($options)
    {
        return $this->runApiMethod('complete/', $options, self::METHOD_POST);
    }

    /**
     * Basic method for all api calls
     * @param $uri
     * @param array $formParams
     * @param string $method
     * @param array $multipart
     * @return mixed
     */
    protected function runApiMethod($uri, $formParams = [], $method = self::METHOD_GET, $multipart = [])
    {
        $response = $this->makeRequest($uri, $formParams, $method, $multipart);
        return $response->getData();
    }


    /**
     * @param $uri
     * @param $formParams
     * @param string $method
     * @param array $multipart
     * @return ApiResponse
     * @throws BadResponseException
     */
    protected function makeRequest($uri, $formParams, $method = 'POST', $multipart = [])
    {
        $options = [
            'form_params' => $formParams,
            'headers' => [
                'Authorization' => 'APIKey ' . $this->apiKey
            ]
        ];

        if ($multipart) {
            unset($options['form_params']);
            $options['multipart'] = $multipart;
        }

        $response = $this->httpClient->request($method, $uri, $options);

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