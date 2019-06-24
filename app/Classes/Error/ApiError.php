<?php
namespace App\Classes\Error;

use App\Classes\Error\BaseApiError;

class ApiError extends BaseApiError
{
    //資源驗證錯誤
    const INVALID_INPUT = 1001;
    const RESOURCE_NOT_FOUND = 1002;
    const USERNAME_EXIST = 1003;

    //伺服器端錯誤
    const INTERNAL_SERVER_ERROR = 4001;


    protected $errorMessage = array(
        self::INVALID_INPUT => '無效的參數。'
        , self::RESOURCE_NOT_FOUND => '不存在的資料。'
        , self::USERNAME_EXIST => '使用者名稱已存在。'

        , self::INTERNAL_SERVER_ERROR => '內部伺服器錯誤。'
    );
}
