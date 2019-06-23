<?php

namespace App\Classes\ErrorHandleableReturns;

use App\Classes\BaseError;
use App\Classes\Error;

abstract class ErrorHandleableReturnBase
{
    protected $value = null;
    protected $error = null;

    /**
     * ErrorHandleableReturnBase constructor.
     *
     * @param               $value
     * @param BaseError $error
     */
    public function __construct($value, BaseError $error = null)
    {
        $this->value = $value;
        if (is_null($error)) {
            $this->error = new Error();
        } else {
            $this->error = $error;
        }
    }

    /**
     * 是否有錯誤
     *
     * @return bool
     */
    public function hasError() : bool
    {
        if (is_null($this->error) or $this->error->getCode() == Error::NO_ERROR) {
            return false;
        }

        return true;
    }

    /**
     * 取得錯誤
     *
     * @return BaseError
     */
    public function getError() : BaseError
    {
        return $this->error;
    }

    /**
     * 取得回傳值
     *
     * @return mixed
     */
    abstract public function getValue();
}
