<?php
namespace App\Classes\Tools;

class SqlQueryHelper
{
    /**
     * 取得當前資料庫時間的語法
     *
     * @return string
     */
    public static function getSyntaxOfCurrentSqlTimestamp() : string
    {
        $queryStringOfCurrentTimestamp = 'UNIX_TIMESTAMP(CURRENT_TIMESTAMP)';
        if (config('database.default') == 'sqlite_testing') {
            $queryStringOfCurrentTimestamp = "STRFTIME('%s','NOW')";
        }

        return $queryStringOfCurrentTimestamp;
    }
}