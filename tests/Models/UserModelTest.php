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
        $expectedError = new Error(ERROR::RESOURCE_NOT_FOUND);

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
        $expectedError = new Error(ERROR::DATABASE_ERROR);

        DB::shouldReceive('select')->once()
            ->andThrow('PDOException');

        $userModel = new UserModel();
        $result = $userModel->getById(1);

        $this->assertTrue($result->hasError());
        $this->assertEquals($expectedError, $result->getError());
    }
}