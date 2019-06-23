<?php
namespace Tests\Classes\ErrorHandleableReturns;

use Tests\TestCase;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnInteger;
use App\Classes\Error;

class ErrorHandleableReturnIntegerTest extends TestCase
{
    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $expectedInteger = 10;
        $expectedError = new Error(Error::INVALID_INPUT);

        $returnValue = new ErrorHandleableReturnInteger($expectedInteger, $expectedError);

        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedInteger, $returnValue->getValue());
        $this->assertEquals($expectedError, $returnValue->getError());
    }

    /**
     * 測試是否有錯誤
     *
     * @return void
     */
    public function testHasError()
    {
        $expectedError = new Error(Error::INVALID_INPUT);

        $returnValue = new ErrorHandleableReturnInteger(10, $expectedError);

        $this->assertTrue($returnValue->hasError());
    }

    /**
     * 測試是否有錯誤，如果沒有錯誤
     *
     * @return void
     */
    public function testHasErrorIfNoError()
    {
        $expectedInteger = 10;

        $returnValue = new ErrorHandleableReturnInteger($expectedInteger);

        $this->assertFalse($returnValue->hasError());
    }
}
