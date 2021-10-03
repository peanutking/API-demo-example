<?php
namespace App\Models;

use App\Classes\DatabaseResource\DatabaseResource;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnDatabaseResource;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnArray;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnObject;
use App\Classes\Tools\QueryExecutor;
use App\Classes\Error\Error;

abstract class DatabaseResourceModel
{
    /**
     * 取得資料表名稱
     *
     * @return string
     */
    abstract protected function getTableName() : string;

    /**
     * 取得 Id 欄位名稱
     *
     * @return string
     */
    abstract protected function getIdFieldName() : string;

    /**
     * 建立資料表對應實例化物件
     *
     * @return string
     */
    abstract protected function createDatabaseResourceInstance() : DatabaseResource;

    /**
     * 透過指定Id取得物件
     *
     * @param int $id
     * @return ErrorHandleableReturnDatabaseResource
     */
    public function getById(int $id) : ErrorHandleableReturnDatabaseResource
    {
        $sql = sprintf("
            SELECT *
            FROM  `%s`
            WHERE `%s` = :userId"
        , $this->getTableName()
        , $this->getIdFieldName());

        $bindingValues = array(
            'userId' => $id
        );

        return $this->selectResource($sql, $bindingValues);
    }

    /**
     * 查詢
     *
     * @param  string $sqlQuerySyntax
     * @param  array  $queryValues
     * @return ErrorHandleableReturnDatabaseResource
     */
    protected function selectResource(string $sqlQuerySyntax, array $queryValues = array()) : ErrorHandleableReturnDatabaseResource
    {
        $selectedReturns = QueryExecutor::select($sqlQuerySyntax, $queryValues);
        return $this->handleSelectReturn($selectedReturns);
    }

    /**
     * 處理查詢結果
     *
     * @param  ErrorHandleableReturnArray $selectedReturns
     * @return ErrorHandleableReturnDatabaseResource
     */
    private function handleSelectReturn(ErrorHandleableReturnArray $selectedReturns)
    {
        if ($selectedReturns->hasError()) {
            return new ErrorHandleableReturnDatabaseResource(
                $this->createDatabaseResourceInstance(),
                $selectedReturns->getError()
            );
        }

        $selectedValues = $selectedReturns->getValue();
        if (empty($selectedValues)) {
            return new ErrorHandleableReturnDatabaseResource(
                $this->createDatabaseResourceInstance(),
                new Error(Error::RESOURCE_NOT_FOUND)
            );
        }

        $databaseResource = $this->createDatabaseResourceInstance();
        $databaseResource->loadFromArray(array_shift($selectedValues));
        return new ErrorHandleableReturnDatabaseResource($databaseResource);
    }
}