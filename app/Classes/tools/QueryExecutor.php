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
     * @param array $bindings
     * @return ErrorHandleableReturnBoolean
     */
    public static function insert(string $sqlQuery, array $bindings = array()) : ErrorHandleableReturnBoolean
    {
        try {
            $isInserted = DB::insert($sqlQuery, $bindings);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnBoolean(false, new Error(Error::DATABASE_ERROR));
        }
        return new ErrorHandleableReturnBoolean($isInserted);
    }

    /**
     * 在資料庫中執行搜尋語法
     *
     * @param string $sqlQuery
     * @param array $bindings
     * @return ErrorHandleableReturnArray
     */
    public static function select(string $sqlQuery, array $bindings = array()) : ErrorHandleableReturnArray
    {
        try {
            $selectResult = DB::select($sqlQuery, $bindings);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnArray(array(), new Error(Error::DATABASE_ERROR));
        }

        return new ErrorHandleableReturnArray($selectResult);
    }

    /**
     * 在資料庫中執行更新語法
     *
     * @param string $sqlQuery
     * @param array $bindings
     * @return ErrorHandleableReturnInteger
     */
    public static function update(string $sqlQuery, array $bindings = array()) : ErrorHandleableReturnInteger
    {
        try {
            $effectedRows = DB::update($sqlQuery, $bindings);
        } catch (\PDOException $pdoException) {
            return new ErrorHandleableReturnInteger(0, new Error(Error::DATABASE_ERROR));
        }
        return new ErrorHandleableReturnInteger($effectedRows);
    }

    /**
     * 在資料庫中執行刪除語法
     *
     * @param string $sqlQuery
     * @param array $bindings
     * @return ErrorHandleableReturnInteger
     */
    public static function delete(string $sqlQuery, array $bindings = array()) : ErrorHandleableReturnInteger
    {
        try {
            $effectedRows = DB::delete($sqlQuery, $bindings);
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
