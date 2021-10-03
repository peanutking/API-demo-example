<?php
namespace Tests\Classes\Creators\Situations;

use Tests\TestCase;
use App\Classes\Error\ApiError;
use App\Classes\Error\Error;
use App\Classes\Tools\ErrorResponseSituation;
use Illuminate\Http\Response;

class ErrorResponseSituationTest extends TestCase
{
    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $situation = new ErrorResponseSituation(array(Error::INVALID_INPUT));

        $this->assertEquals(array(Error::INVALID_INPUT), $situation->getErrorCodes());
        $this->assertEmpty($situation->getCustomErrorMessage());
        $this->assertEquals(0, $situation->getStatusCode());
    }

    /**
     * 測試Setter與Getter
     *
     * @return void
     */
    public function testSetterAndGetter()
    {
        $expectedErrorCodes = array(Error::INVALID_INPUT);
        $expectedApiError = new ApiError(ApiError::INTERNAL_SERVER_ERROR);

        $situation = new ErrorResponseSituation(array(Error::INVALID_INPUT));

        $situation->setApiError(new ApiError(ApiError::INTERNAL_SERVER_ERROR));
        $situation->setCustomErrorMessage('test');
        $situation->setStatusCode(Response::HTTP_BAD_REQUEST);

        $this->assertEquals($expectedErrorCodes, $situation->getErrorCodes());
        $this->assertEquals($expectedApiError, $situation->getApiError());
        $this->assertEquals('test', $situation->getCustomErrorMessage());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $situation->getStatusCode());
    }
}
