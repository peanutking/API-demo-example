<?php
namespace App\Classes\ErrorHandleableReturns;

use App\Classes\DatabaseResource\DatabaseResource;
use App\Classes\Error\Error;

class ErrorHandleableReturnDatabaseResource extends ErrorHandleableReturnBase
{
    /**
     * ErrorHandleableReturnDatabaseResource constructor.
     *
     * @param DatabaseResource $databaseResource
     * @param Error            $error
     */
    public function __construct(DatabaseResource $databaseResource, Error $error = null)
    {
        parent::__construct($databaseResource, $error);
    }

    /**
     * 取得回傳值
     *
     * @return DatabaseResource
     */
    public function getValue() : DatabaseResource
    {
        return $this->value;
    }
}
