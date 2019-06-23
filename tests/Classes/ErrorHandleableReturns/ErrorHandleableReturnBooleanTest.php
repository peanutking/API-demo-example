<?php
namespace Tests\Classes\ErrorHandleableReturns;

use Tests\TestCase;
use App\Classes\Error;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnBoolean;

class ErrorHandleableReturnBooleanTest extends TestCase
{
    /**
     * 測試是否有錯誤
     *
     * @return void
     */
    public function testHasError()
    {
        $returnValue = new ErrorHandleableReturnBoolean(false, new Error(Error::INVALID_INPUT));

        $this->assertTrue($returnValue->hasError());
    }

    /**
     * 測試是否有錯誤，若未定義錯誤代碼
     *
     * @return void
     */
    public function testHasErrorIfReturnWithoutError()
    {
        $returnValue = new ErrorHandleableReturnBoolean(true);

        $this->assertFalse($returnValue->hasError());
        $this->assertEquals(Error::NO_ERROR, $returnValue->getError()->getCode());
    }

    /**
     * 測試取得錯誤
     *
     * @return void
     */
    public function testGetError()
    {
        $returnValue = new ErrorHandleableReturnBoolean(false, new Error(Error::INVALID_INPUT));

        $this->assertEquals(new Error(Error::INVALID_INPUT), $returnValue->getError());
    }

    /**
     * 測試取得回傳值
     *
     * @return void
     */
    public function testGetValue()
    {
        $returnValue = new ErrorHandleableReturnBoolean(true);

        $this->assertTrue($returnValue->getValue());
    }
}
