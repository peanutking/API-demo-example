<?php
namespace App\Classes\ErrorHandleableReturns;

use App\Classes\Error\BaseError;

class ErrorHandleableReturnBoolean extends ErrorHandleableReturnBase
{
    /**
     * ErrorHandleableReturnBoolean constructor.
     *
     * @param bool $value
     * @param BaseError $error
     */
    public function __construct(bool $value, BaseError $error = null)
    {
        parent::__construct($value, $error);
    }

    /**
     * 取得回傳值
     *
     * @return bool
     */
    public function getValue() : bool
    {
        return $this->value;
    }
}
