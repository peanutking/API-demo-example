<?php
namespace App\Classes;

class Error extends BaseError
{
    //Resource
    const INVALID_INPUT = 1001;
    const RESOURCE_NOT_FOUND = 1002;
    const FORBIDDEN = 1003;
    
    // Database
    const DATABASE_ERROR  = 2001;

    protected $errorMessage = array(
      self::INVALID_INPUT => '無效的參數。'
      , self::RESOURCE_NOT_FOUND => '不存在的資料。'
      , self::FORBIDDEN => '權限不足'
      , self::DATABASE_ERROR  => '資料庫錯誤。'
    );
}