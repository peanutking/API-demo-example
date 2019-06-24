<?php

namespace App\Classes\Tools;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseCreator
{
    const HEADER = array( 'Content-type'=>'application/json; charset=utf-8'
                        , 'Cache-Control'=>'no-cache, private');

    /**
     * 內容回應
     *
     * @param  array        $content
     * @param  int          $statusCode
     * @param  array        $header
     * @return JsonResponse
     */
    public static function createResponse(array $content, int $statusCode, array $header = array()) : JsonResponse
    {
        $responseHeader = array_merge(self::HEADER, $header);

        return new JsonResponse($content, $statusCode, $responseHeader, JSON_UNESCAPED_UNICODE);
    }
}
