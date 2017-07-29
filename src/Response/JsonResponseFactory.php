<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.10.2016
 * Time: 9:35
 */

namespace ShareCloth\Look\Api\Response;


class JsonResponseFactory extends AbstractResponseFactory
{

    public function getApiResponse($data)
    {
        $data = json_decode($data, true);

        $apiResponse = new ApiResponse();
        $apiResponse->setStatus(ApiResponse::STATUS_SUCCESS);
        $apiResponse->setData($data);

        return $apiResponse;
    }
}