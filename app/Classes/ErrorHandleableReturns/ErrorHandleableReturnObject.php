<?php
namespace App\Classes\ErrorHandleableReturns;

use App\Classes\BaseError;

class ErrorHandleableReturnObject extends ErrorHandleableReturnBase
{
    /**
     * ErrorHandleableReturnObject constructor.
     *
     * @param $object
     * @param BaseError $error
     */
    public function __construct($object, BaseError $error = null)
    {
        if (is_object($object)) {
            parent::__construct($object, $error);
        } else {
            parent::__construct(null, $error);
        }
    }

    /**
     * 取得回傳值
     *
     * @return object
     */
    public function getValue()
    {
        return $this->value;
    }
}