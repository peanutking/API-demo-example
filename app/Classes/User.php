<?php
namespace App\Classes;

class User implements DatabaseResource
{
    private $ixUser = 0;
    private $sUsername = '';
    private $sPassword;
    private $iCreatedTimestamp = null;
    private $iUpdatedTimestamp = null;

    /**
     * 設定使用者編號
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id) : void
    {
        $this->ixUser = $id;
    }

    /**
     * 設定使用者名稱
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username) : void
    {
        $this->sUsername = $username;
    }

    /**
     * 設定使用者密碼
     *
     * @param string $password
     * @return void
     */
    public function setEncryptedPassword(string $password) : void
    {
        $this->sPassword = $password;
    }

    /**
     * 設定建立時間
     *
     * @param int $createdTimestamp
     * @return void
     */
    public function setCreatedTimestamp(int $createdTimestamp) : void
    {
        $this->iCreatedTimestamp = $createdTimestamp;
    }

    /**
     * 設定更新時間
     *
     * @param int $createdTimestamp
     * @return void
     */
    public function setUpdatedTimestamp(int $updateTimestamp) : void
    {
        $this->iUpdatedTimestamp = $updateTimestamp;
    }

    /**
     * 取得使用者編號
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->ixUser;
    }

    /**
     * 取得使用者名稱
     *
     * @return string
     */
    public function getUsername() : string
    {
        return $this->sUsername;
    }

    /**
     * 檢查碼是否正確
     *
     * @param string $password
     * @return bool
     */
    public function isPasswordCorrect(string $password) : bool
    {
        return password_verify($password, $this->sPassword);
    }

    /**
     *  取得使用者建立時間
     *
     * @return int | null
     */
    public function getCreatedTimestamp()
    {
        return $this->iCreatedTimestamp;
    }

    /**
     *  取得使用者更新時間
     *
     * @return int | null
     */
    public function getUpdatedTimestamp()
    {
        return $this->iUpdatedTimestamp; 
    }

    /**
     * 透過陣列載入
     *
     * @param array $content
     * @return void
     */
    public function loadFromArray(array $content) : void
    {
        if (isset($content['ixUser'])) {
            $this->setId($content['ixUser']);
        }
        if (isset($content['sUsername'])) {
            $this->setUsername($content['sUsername']);
        }
        if (isset($content['sPassword'])) {
            $this->setEncryptedPassword($content['sPassword']);
        }
        if (isset($content['iCreatedTimestamp'])) {
            $this->setCreatedTimestamp($content['iCreatedTimestamp']);
        }
        if (isset($content['iUpdatedTimestamp'])) {
            $this->setUpdatedTimestamp($content['iUpdatedTimestamp']);
        }
    }
}