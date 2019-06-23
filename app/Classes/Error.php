<?php
namespace App\Classes;

class Error extends BaseError
{
    //Resource
    const INVALID_INPUT = 1001;
    const RESOURCE_NOT_FOUND = 1002;
    
    // Database
    const DATABASE_ERROR  = 2001;

    protected $errorMessage = array(
        self::INVALID_INPUT => '无效的参数。'
      , self::RESOURCE_NOT_FOUND => '不存在的资料。'
      , self::DATABASE_ERROR  => '资料库错误。'
    );
}