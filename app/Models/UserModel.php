<?php
namespace App\Models;

use App\Classes\DatabaseResource\User;
use App\Classes\DatabaseResource\DatabaseResource;

class UserModel extends DatabaseResourceModel
{
    /**
     * 取得資料表名稱
     *
     * @return string
     */
    protected function getTableName() : string
    {
        return 'user';
    }

    /**
     * 取得 Id 欄位名稱
     *
     * @return string
     */
    protected function getIdFieldName() : string
    {
        return 'ixUser';
    }

    /**
     * 建立資料表對應實例化物件
     *
     * @return DatabaseResource
     */
    protected function createDatabaseResourceInstance() : DatabaseResource
    {
        return new User();
    }
}