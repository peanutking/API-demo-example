<?php
namespace Tests\Classes\Tools;

use Tests\TestCase;
use App\Classes\Tools\ResponseCreator;
use Illuminate\Http\Response;

class ResponseCreatorTest extends TestCase
{
    /**
     * 測試內容回應
     *
     * @return void
     */
    public function testCreateResponse()
    {
        $expectedContent = array();
        $expectedContent['id'] = 10;
        $expectedContent['status'] = 'recording';
        $expectedResponse = json_encode($expectedContent, JSON_UNESCAPED_UNICODE);

        $response = ResponseCreator::createResponse($expectedContent, Response::HTTP_OK);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals('application/json; charset=utf-8', $response->headers->get('Content-type'));
        $this->assertEquals('no-cache, private', $response->headers->get('Cache-Control'));
    }
}
