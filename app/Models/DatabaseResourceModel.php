<?php
namespace App\Models;

use App\Classes\DatabaseResource\DatabaseResource;
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
     * @return ErrorHandleableReturnObject
     */
    public function getById(int $id) : ErrorHandleableReturnObject
    {
        $sql = sprintf("
            SELECT *
            FROM  `%s`
            WHERE `%s` = %u"
        , $this->getTableName()
        , $this->getIdFieldName()
        , $id);

        $returnValue = QueryExecutor::select($sql);
        if ($returnValue->hasError()) {
            return new ErrorHandleableReturnObject(
                static::createDatabaseResourceInstance(),
                $returnValue->getError()
            );
        }
    
        if (empty($returnValue->getValue())) {
            return new ErrorHandleableReturnObject(
                static::createDatabaseResourceInstance(),
                new Error(Error::RESOURCE_NOT_FOUND)
            );
        }

        $selectValue = $returnValue->getValue();
        $object = static::createDatabaseResourceInstance();
        $object->loadFromArray($selectValue[0]);
        return new ErrorHandleableReturnObject($object);
    }
}