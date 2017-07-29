<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.10.2016
 * Time: 15:37
 */

namespace ShareCloth\Look\Api;


interface ClientInterface
{
    public function __construct($apiKey, $httpClientConfig = []);

    public function getApiKey();

    public function personCustomBody($options);

    public function avatarList($options = []);

}