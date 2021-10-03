<?php
namespace App\Classes\ErrorHandleableReturns;

use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnDatabaseResource;
use App\Classes\Error\Error;
use App\Classes\DatabaseResource\User;
use Tests\TestCase;

class ErrorHandleableReturnDatabaseResourceTest extends TestCase
{
    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $expectedKline = new User();
        $expectedKline->setId(10);
        $expectedError = new Error(Error::INVALID_INPUT);

        $returnValue = new ErrorHandleableReturnDatabaseResource($expectedKline, $expectedError);

        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedKline, $returnValue->getValue());
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

        $returnValue = new ErrorHandleableReturnDatabaseResource(new User(), $expectedError);

        $this->assertTrue($returnValue->hasError());
    }

    /**
     * 測試是否有錯誤，如果沒有錯誤
     *
     * @return void
     */
    public function testHasErrorIfNoError()
    {
        $expectedKline = new User();
        $expectedKline->setId(10);
        $returnValue = new ErrorHandleableReturnDatabaseResource($expectedKline);

        $this->assertFalse($returnValue->hasError());
    }
}
