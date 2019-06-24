<?php
namespace Tests\Classes\ErrorHandleableReturns;

use Tests\TestCase;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnArray;
use App\Classes\Error\Error;

class ErrorHandleableReturnArrayTest extends TestCase
{
    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $expectedArray = array(10);
        $expectedError = new Error(Error::INVALID_INPUT);

        $returnValue = new ErrorHandleableReturnArray($expectedArray, $expectedError);

        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedArray, $returnValue->getValue());
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

        $returnValue = new ErrorHandleableReturnArray(array(10), $expectedError);

        $this->assertTrue($returnValue->hasError());
    }

    /**
     * 測試是否有錯誤，如果沒有錯誤
     *
     * @return void
     */
    public function testHasErrorIfNoError()
    {
        $expectedArray = array(10);

        $returnValue = new ErrorHandleableReturnArray($expectedArray);

        $this->assertFalse($returnValue->hasError());
    }
}
