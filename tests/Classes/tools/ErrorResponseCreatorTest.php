<?php
namespace Tests\Classes\Tools;

use Tests\TestCase;
use App\Classes\Error\ApiError;
use App\Classes\Tools\ErrorResponseCreator;
use App\Classes\Error\Error;
use Illuminate\Http\Response;

class ErrorResponseCreatorTest extends TestCase
{
    /**
     * 測試建立回應
     *
     * @return void
     */
    public function testCreateResponse()
    {
        $error = new Error(Error::INVALID_INPUT);
        $errorResponseCreator = new ErrorResponseCreator($error);
        $errorResponseCreator
            ->whenErrorIs(Error::INVALID_INPUT)
                ->willRespondStatusCode(Response::HTTP_BAD_REQUEST)
                ->willRespondApiError(new ApiError(ApiError::INVALID_INPUT))
                ->willResponseCustomErrorMessage('Invalid operation.')
            ->whenErrorIs(Error::DATABASE_ERROR)
                ->willRespondStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->willRespondApiError(new ApiError(ApiError::INTERNAL_SERVER_ERROR))
                ->willResponseCustomErrorMessage('未知的錯誤。');

        $expectedResponse = array();
        $expectedResponse['error_code'] = ApiError::INVALID_INPUT;
        $expectedResponse['error_message'] = 'Invalid operation.';

        $response = $errorResponseCreator->create();
        $decodedResponse = json_decode($response->content(), true);
        $this->assertEquals($expectedResponse, $decodedResponse);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $expectedResponse = array();
        $expectedResponse['error_code'] = ApiError::INTERNAL_SERVER_ERROR;
        $expectedResponse['error_message'] = '未知的錯誤。';

        $errorResponseCreator->setError(new Error(Error::DATABASE_ERROR));
        $response = $errorResponseCreator->create();
        $decodedResponse = json_decode($response->content(), true);
        $this->assertEquals($expectedResponse, $decodedResponse);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    /**
     * 測試建立回應不在對照組當中
     *
     * @return void
     */
    public function testCreateResponseNotInControlGroup()
    {
        $errorResponseCreator = new ErrorResponseCreator(new Error(Error::DATABASE_ERROR));

        $response = $errorResponseCreator->create();
        $decodedResponse = json_decode($response->content(), true);

        $this->assertEquals('未知的錯誤。', $decodedResponse);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}
