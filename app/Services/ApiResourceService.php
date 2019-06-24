<?php
namespace App\Services;

use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnBoolean;
use App\Models\UserModel;
use App\Classes\DatabaseResource\User;
use App\Classes\Error\Error;

class ApiResourceService
{
    // Api parameters
    private $targetUsername = null;
    private $targetUserId = null;

    // Resource
    private $targetUser = null;

    /**
     * 設定目標使用者名稱
     *
     * @param string $username
     * @return void
     */
    public function setTargetUsername(string $username)
    {
        $this->targetUsername = $username;
    }

    /**
     * 設定目標使用者編號
     *
     * @param string $userId
     * @return void
     */
    public function setTargetUserId(int $userId)
    {
        $this->targetUserId = $userId;
    }

    /**
     * 取得目標使用者
     *
     * @return User
     */
    public function getTargetUser() : User
    {
        return $this->targetUser;
    }

    /**
     * 載入目標使用者
     *
     * 需求參數: targetUsername/targetUserId
     *
     * @return ErrorHandleableReturnBoolean
     */
    public function loadTargetUser() : ErrorHandleableReturnBoolean
    {
        if (is_null($this->targetUsername)
            and is_null($this->targetUserId)) {
            return new ErrorHandleableReturnBoolean(
                false,
                new Error(Error::INVALID_INPUT)
            );
        }

        $userModel = new UserModel();
        if (isset($this->targetUsername))  {
            $getUserResult = $userModel->getByUsername($this->targetUsername);
        } else {
            $getUserResult = $userModel->getById($this->targetUserId);
        }

        if ($getUserResult->hasError()) {
            return new ErrorHandleableReturnBoolean(
                false,
                $getUserResult->getError()
            );
        }
        $this->targetUser = $getUserResult->getValue();
        return new ErrorHandleableReturnBoolean(false);
    }
}