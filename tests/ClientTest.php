<?php

use ShareCloth\Look\Api\Client;

/**
 * Class ClientTest
 */
class ClientTest extends PHPUnit_Framework_TestCase
{

    /** @var  Client */
    protected $_client;

    /**
     *
     */
    public function setUp()
    {
        $this->_client = new Client(API_ACCESS_TOKEN, ['base_uri' => API_URI]);
        $this->assertNotEmpty($this->_client->getApiSecret(), 'Api secret is empty');
        parent::setUp();
    }

}
