<?php
namespace App\Models;

use App\Classes\DatabaseResource\User;
use App\Classes\DatabaseResource\DatabaseResource;
use App\Classes\Error\Error;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnBoolean;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnDatabaseResource;
use App\Classes\Tools\QueryExecutor;
use App\Classes\Tools\SqlQueryHelper;

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

    /**
     * 新增使用者
     *
     * @param User $newUser
     * @param string $encryptedPassword
     * @return ErrorHandleableReturnBoolean
     */
    public function insertNewUser(User $newUser, string $encryptedPassword) : ErrorHandleableReturnBoolean
    {
        $sql = sprintf("
            INSERT INTO `user`
                (`sUsername`, `sPassword`, `iCreatedTimestamp`)
            VALUES
                (:username, :password, %s)"
            , SqlQueryHelper::getSyntaxOfCurrentSqlTimestamp());
        $bindingValues = array(
            'username' => $newUser->getUsername(),
            'password' => $encryptedPassword
        );
        $insertResult = QueryExecutor::insert($sql, $bindingValues);
        if ($insertResult->hasError()) {
            return new ErrorHandleableReturnBoolean(
                false,
                $insertResult->getError()
            );
        }
        if ($insertResult->getValue() == false) {
            return new ErrorHandleableReturnBoolean(false, new Error(Error::RESOURCE_NOT_BUILD));
        }

        $getLatestIdReturnValue = QueryExecutor::getLastInsertId();
        if ($getLatestIdReturnValue->hasError()) {
            return new ErrorHandleableReturnBoolean(false, $getLatestIdReturnValue->getError());
        }
        $latestId = $getLatestIdReturnValue->getValue();
        $newUser->setId($latestId);
        return new ErrorHandleableReturnBoolean(true);
    }

    /**
     * 透過使用者編號更新使用者密碼
     *
     * @param int $userId
     * @param string $encryptedPassword
     * @return ErrorHandleableReturnBoolean
     */
    public function updatePasswordByUserId(int $userId, string $encryptedPassword) : ErrorHandleableReturnBoolean
    {
        $sql = sprintf("
            UPDATE `user`
            SET    `sPassword` = :password
                ,  `iUpdatedTimestamp` = %s
            WHERE  `ixUser` = :userId"
        , SqlQueryHelper::getSyntaxOfCurrentSqlTimestamp());

        $bindingValues = array(
            'password' => $encryptedPassword,
            'userId' => $userId
        );
        $updateResult = QueryExecutor::update($sql, $bindingValues);
        if ($updateResult->hasError()) {
            return new ErrorHandleableReturnBoolean(
                false,
                $updateResult->getError()
            );
        }

        $effectedRow = $updateResult->getValue();
        return new ErrorHandleableReturnBoolean($effectedRow > 0);
    }

    /**
     * 透過使用者名稱取得使用者
     *
     * @param string $username
     * @return ErrorHandleableReturnDatabaseResource
     */
    public function getByUsername(string $username) : ErrorHandleableReturnDatabaseResource
    {
        $sql = "
            SELECT *
            FROM `user`
            WHERE `sUsername` = :username";

        $bindingValues = array(
            'username' => $username
        );
        return $this->selectResource($sql, $bindingValues);
    }

    /**
     * 透過使用者編號刪除使用者
     *
     * @param string $username
     * @return ErrorHandleableReturnBoolean
     */
    public function deleteById(int $userId) : ErrorHandleableReturnBoolean
    {
        $sql = "
            DELETE FROM `user`
            WHERE `ixUser` = :userId";
        $bindingValues = array(
            'userId' => $userId
        );
        $deleteResult = QueryExecutor::delete($sql, $bindingValues);
        if ($deleteResult->hasError()) {
            return new ErrorHandleableReturnBoolean(
                false,
                $deleteResult->getError()
            );
        }
        $effectedRow = $deleteResult->getValue();
        return new ErrorHandleableReturnBoolean($effectedRow > 0);
    }
}