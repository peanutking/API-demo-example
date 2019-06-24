<?php
namespace Tests\Classes\ErrorHandleableReturns;

use Tests\TestCase;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnObject;
use App\Classes\Error\Error;
use App\Classes\DatabaseResource\User;

class ErrorHandleableReturnObjectTest extends TestCase
{
    /**
     * 測試建構子
     *
     * @return void
     */
    public function testConstructor()
    {
        $expectedUser = new User();
        $expectedUser->setId(10);
        $expectedError = new Error(Error::INVALID_INPUT);

        $returnValue = new ErrorHandleableReturnObject($expectedUser, $expectedError);

        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedUser, $returnValue->getValue());
        $this->assertEquals($expectedError, $returnValue->getError());
    }

    /**
     * 測試給予非物件的參數作為建構子參數，取得回傳值應為 null
     *
     * @return void
     */
    public function testConstructorIfNotObject()
    {
        $expectedError = new Error(Error::INVALID_INPUT);

        $returnValue = new ErrorHandleableReturnObject(array(), $expectedError);

        $this->assertTrue($returnValue->hasError());
        $this->assertNull($returnValue->getValue());
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

        $returnValue = new ErrorHandleableReturnObject(new User(), $expectedError);

        $this->assertTrue($returnValue->hasError());
    }

    /**
     * 測試是否有錯誤，如果沒有錯誤
     *
     * @return void
     */
    public function testHasErrorIfNoError()
    {
        $expectedUser = new User();
        $expectedUser->setId(10);
        $returnValue = new ErrorHandleableReturnObject($expectedUser);

        $this->assertFalse($returnValue->hasError());
    }
}
