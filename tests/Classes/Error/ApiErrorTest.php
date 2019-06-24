<?php
namespace Tests\Classes;

use Tests\TestCase;
use App\Classes\Error\ApiError;

class ApiErrorTest extends TestCase
{
    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $error = new ApiError(ApiError::INVALID_INPUT);
        $expectedCode = $error->getCode();
        $expectedMessage = $error->getMessage();

        $error = new ApiError($expectedCode);
        $this->assertEquals($expectedCode, $error->getCode());
        $this->assertEquals($expectedMessage, $error->getMessage());
    }

    /**
     * 測試取得錯誤訊息
     *
     * @return void
     */
    public function testGetMessage()
    {
        $error = new ApiError();
        $error->setCode(ApiError::INVALID_INPUT);

        $this->assertEquals('無效的參數。', $error->getMessage());
    }

    /**
     * 測試匯出為回應內容
     *
     * @return void
     */
    public function testToResponseContent()
    {
        $expectedContent = array();
        $expectedContent['error_code'] = ApiError::INVALID_INPUT;
        $expectedContent['error_message'] = '無效的參數。';

        $error = new ApiError();
        $error->setCode(ApiError::INVALID_INPUT);

        $this->assertEquals($expectedContent, $error->toResponseContent());
    }
}
