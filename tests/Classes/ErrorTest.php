<?php
namespace Tests\Classes;

use Tests\TestCase;
use App\Classes\Error;

class ErrorTest extends TestCase
{

    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $error = new Error(Error::RESOURCE_NOT_FOUND);
        $expectedCode = $error->getCode();
        $expectedMessage = $error->getMessage();

        $error = new Error($expectedCode);
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
        $error = new Error();
        $error->setCode(Error::INVALID_INPUT);

        $this->assertEquals('無效的參數。', $error->getMessage());
    }

    /**
     * 測試預設錯誤
     *
     * @return void
     */
    public function testDefaultError()
    {
        $error = new Error();
        $this->assertEquals(Error::NO_ERROR, $error->getCode());
    }
}
