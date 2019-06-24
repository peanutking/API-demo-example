<?php
namespace Tests\Models;

use App\Classes\DatabaseResource\User;
use App\Classes\Error\Error;
use App\Models\UserModel;
use Tests\DatabaseTestCase;
use Illuminate\Support\Facades\DB;

class UserModelTest extends DatabaseTestCase
{
    /**
     * 測試透過編號取得物件
     *
     * @return void
     */
    public function testGetById()
    {
        $content['ixUser'] = 1;
        $content['sUsername'] = 'Alex';
        $content['sPassword'] = '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou';
        $content['iCreatedTimestamp'] = 123456789;
        $content['iUpdatedTimestamp'] = null;
        $expectedUser = new User();
        $expectedUser->loadFromArray($content);

        $userModel = new UserModel();
        $result = $userModel->getById(1);

        $this->assertEquals($expectedUser, $result->getValue());
    }

    /**
     * 測試透過編號取得物件，如果編號不存在
     *
     * @return void
     */
    public function testGetByIdIfIdNotExist()
    {
        $expectedError = new Error(Error::RESOURCE_NOT_FOUND);

        $userModel = new UserModel();
        $result = $userModel->getById(99);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }

    /**
     * 測試透過編號取得物件，如果資料庫發生資料庫發生錯誤
     *
     * @return void
     */
    public function testGetByIdIfDatabaseErrorOccurred()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        DB::shouldReceive('select')->once()
            ->andThrow('PDOException');

        $userModel = new UserModel();
        $result = $userModel->getById(1);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }

    /**
     * 測試新增使用者
     *
     * @return void
     */
    public function testInsertNewUser()
    {
        $user = new User();
        $user->setUsername('Peter');
        $encryptedPassword = password_hash('123456789', PASSWORD_BCRYPT);
        $userModel = new UserModel();
        $result = $userModel->insertNewUser($user, $encryptedPassword);

        $this->assertFalse($result->hasError());
        $this->assertEquals(5, $user->getId());
    }

    /**
     * 測試新增使用者，如果發生資料庫錯誤
     *
     * @return void
     */
    public function testInsertNewUserIfDatabaseErrorOccurred()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        $user = new User();
        $user->setUsername('Peter');
        $encryptedPassword = password_hash('123456789', PASSWORD_BCRYPT);

        DB::shouldReceive('insert')->once()
            ->andThrow('PDOException');

        $userModel = new UserModel();
        $result = $userModel->insertNewUser($user, $encryptedPassword);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }

    /**
     * 測試新增使用者，如果取得最新新增編號發生資料庫錯誤
     *
     * @return void
     */
    public function testInsertNewUserIfDataErrorWhenGetLastInsertId()
    {
        $expectedError = new Error(Error::FORBIDDEN);

        $user = new User();
        $user->setUsername('Peter');
        $encryptedPassword = password_hash('123456789', PASSWORD_BCRYPT);

        $pdo = $this->getMockBuilder('PDO')
            ->disableOriginalConstructor()
            ->setMethods(array('lastInsertId'))
            ->getMock();
        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willThrowException(new \PDOException());

        DB::shouldReceive('insert')->once()
            ->andReturn(true);
        
        DB::shouldReceive('getPdo')
            ->once()
            ->andReturn($pdo);    

        $userModel = new UserModel();
        $result = $userModel->insertNewUser($user, $encryptedPassword);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }

    /**
     * 測試透過使用者編號更新使用者密碼
     *
     * @return void
     */
    public function testupdatePasswordByUserId()
    {
        $encryptedPassword = password_hash('987654321', PASSWORD_BCRYPT);

        $userModel = new UserModel();
        $result = $userModel->updatePasswordByUserId(1, $encryptedPassword);

        $this->assertFalse($result->hasError());
        $updateUser = $userModel->getById(1)->getValue();
        $this->assertTrue($updateUser->isPasswordCorrect('987654321'));
    }

    /**
     * 測試透過使用者編號更新使用者密碼，如果發生資料庫錯誤
     *
     * @return void
     */
    public function testupdatePasswordByUserIdIfDatabaseErrorOccurred()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        $encryptedPassword = password_hash('987654321', PASSWORD_BCRYPT);

        DB::shouldReceive('update')->once()
            ->andThrow('PDOException');

        $userModel = new UserModel();
        $result = $userModel->updatePasswordByUserId(1, $encryptedPassword);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }

    /**
     * 測試透過使用者名稱取得物件
     *
     * @return void
     */
    public function testGetByUsername()
    {
        $content['ixUser'] = 1;
        $content['sUsername'] = 'Alex';
        $content['sPassword'] = '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou';
        $content['iCreatedTimestamp'] = 123456789;
        $content['iUpdatedTimestamp'] = null;
        $expectedUser = new User();
        $expectedUser->loadFromArray($content);

        $userModel = new UserModel();
        $result = $userModel->getByUsername('Alex');

        $this->assertEquals($expectedUser, $result->getValue());
    }

    /**
     * 測試透過使用者名稱取得物件，如果發生資料庫錯誤
     *
     * @return void
     */
    public function testGetByUsernameIfDatabaseErrorOccurred()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        DB::shouldReceive('select')->once()
            ->andThrow('PDOException');

        $userModel = new UserModel();
        $result = $userModel->getByUsername('Alex');

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }

        /**
     * 測試透過使用者編號刪除使用者
     *
     * @return void
     */
    public function testDeleteById()
    {
        $userModel = new UserModel();
        $result = $userModel->deleteById(1);

        $this->assertFalse($result->hasError());
        $this->assertTrue($result->getValue());
    }

    /**
     * 測試透過使用者編號刪除使用者，如果發生資料庫錯誤
     *
     * @return void
     */
    public function testDeleteByIdIfDatabaseErrorOccurred()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);
        DB::shouldReceive('delete')->once()
            ->andThrow('PDOException');

        $userModel = new UserModel();
        $result = $userModel->deleteById(1);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }
}