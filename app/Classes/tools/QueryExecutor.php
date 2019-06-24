<?php
namespace App\Classes\Tools;

use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnArray;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnBoolean;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnInteger;
use App\Classes\Error\Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryExecutor
{
    /**
     * 在資料庫中執行新增語法
     *
     * @param string $sqlQuery
     * @return ErrorHandleableReturnBoolean
     */
    public static function insert(string $sqlQuery) : ErrorHandleableReturnBoolean
    {
        try {
            $isInserted = DB::insert($sqlQuery);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnBoolean(false, new Error(Error::DATABASE_ERROR));
        }
        return new ErrorHandleableReturnBoolean($isInserted);
    }

    /**
     * 在資料庫中執行搜尋語法
     *
     * @param string $sqlQuery
     * @return ErrorHandleableReturnArray
     */
    public static function select(string $sqlQuery) : ErrorHandleableReturnArray
    {
        try {
            $selectResult = DB::select($sqlQuery);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnArray(array(), new Error(Error::DATABASE_ERROR));
        }
        $selectResult = json_decode(json_encode($selectResult), true);
        return new ErrorHandleableReturnArray($selectResult);
    }

    /**
     * 在資料庫中執行更新語法
     *
     * @param string $sqlQuery
     * @return ErrorHandleableReturnInteger
     */
    public static function update(string $sqlQuery) : ErrorHandleableReturnInteger
    {
        try {
            $effectedRows = DB::update($sqlQuery);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnInteger(0, new Error(Error::DATABASE_ERROR));
        }
        return new ErrorHandleableReturnInteger($effectedRows);
    }

    /**
     * 在資料庫中執行刪除語法
     *
     * @param string $sqlQuery
     * @return ErrorHandleableReturnInteger
     */
    public static function delete(string $sqlQuery) : ErrorHandleableReturnInteger
    {
        try {
            $effectedRows = DB::delete($sqlQuery);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnInteger(0, new Error(Error::DATABASE_ERROR));
        }
        return new ErrorHandleableReturnInteger($effectedRows);
    }

    /**
     * 取得最新一筆Id
     *
     * @return ErrorHandleableReturnInteger
     */
    public static function getLastInsertId() : ErrorHandleableReturnInteger
    {
        try {
            $latestId = DB::getPdo()->lastInsertId();
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnInteger(0, new Error(Error::FORBIDDEN));
        }
        return new ErrorHandleableReturnInteger($latestId);
    }
}
