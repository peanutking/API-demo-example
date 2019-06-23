<?php
namespace App\Classes\ErrorHandleableReturns;

use App\Classes\BaseError;

class ErrorHandleableReturnInteger extends ErrorHandleableReturnBase
{
    /**
     * ErrorHandleableReturnInteger constructor.
     *
     * @param int           $value
     * @param BaseError $error
     */
    public function __construct(int $value, BaseError $error = null)
    {
        parent::__construct($value, $error);
    }

    /**
     * 取得回傳值
     *
     * @return int
     */
    public function getValue() : int
    {
        return $this->value;
    }
}
