<?php
namespace App\Classes\ErrorHandleableReturns;

use App\Classes\Error\BaseError;

class ErrorHandleableReturnArray extends ErrorHandleableReturnBase
{
    /**
     * ErrorHandleableReturnArray constructor.
     *
     * @param array  $value
     * @param BaseError $error
     */
    public function __construct(array $value, BaseError $error = null)
    {
        parent::__construct($value, $error);
    }

    /**
     * 取得回傳值
     *
     * @return array
     */
    public function getValue() : array
    {
        return $this->value;
    }
}
