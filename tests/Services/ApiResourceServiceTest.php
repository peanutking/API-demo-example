<?php
namespace Tests\Services;

use Tests\DatabaseTestCase;
use App\Services\ApiResourceService;
use App\Classes\DatabaseResource\User;
use App\Classes\Error\Error;
use Illuminate\Support\Facades\DB;

class ApiResourceServiceTest extends DatabaseTestCase
{
    /**
     * 測試透過使用者名稱載入目標使用者
     *
     * @return void
     */
    public function testLoadTargetUserByUsername()
    {
        $content['ixUser'] = 1;
        $content['sUsername'] = 'Alex';
        $content['sPassword'] = '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou';
        $content['iCreatedTimestamp'] = 123456789;
        $content['iUpdatedTimestamp'] = null;
        $expectedUser = new User();
        $expectedUser->loadFromArray($content);

        $apiResourceService = new ApiResourceService();
        $apiResourceService->setTargetUsername('Alex');
        $loadUserResult = $apiResourceService->loadTargetUser();

        $this->assertFalse($loadUserResult->hasError());
        $this->assertEquals($expectedUser, $apiResourceService->getTargetUser());
    }

    /**
     * 測試透過使用者編號載入目標使用者
     *
     * @return void
     */
    public function testLoadTargetUserByUserId()
    {
        $content['ixUser'] = 1;
        $content['sUsername'] = 'Alex';
        $content['sPassword'] = '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou';
        $content['iCreatedTimestamp'] = 123456789;
        $content['iUpdatedTimestamp'] = null;
        $expectedUser = new User();
        $expectedUser->loadFromArray($content);

        $apiResourceService = new ApiResourceService();
        $apiResourceService->setTargetUserId(1);
        $loadUserResult = $apiResourceService->loadTargetUser();

        $this->assertFalse($loadUserResult->hasError());
        $this->assertEquals($expectedUser, $apiResourceService->getTargetUser());
    }

    /**
     * 測試載入目標使用者，如果沒有設定相關參數
     *
     * @return void
     */
    public function testLoadTargetUserIfRelativeParameterIsNull()
    {
        $expectedError = new Error(Error::INVALID_INPUT);
        $apiResourceService = new ApiResourceService();
        $loadUserResult = $apiResourceService->loadTargetUser();

        $this->assertTrue($loadUserResult->hasError());
        $this->assertEquals($expectedError, $loadUserResult->getError());
    }

    /**
     * 測試載入目標使用者時發生資料庫錯誤
     *
     * @return void
     */
    public function testLoadTargetUserIfDatabaseErrorOccurred()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        DB::shouldReceive('select')->once()
            ->andThrow('PDOException');

        $apiResourceService = new ApiResourceService();
        $apiResourceService->setTargetUserId(1);
        $loadUserResult = $apiResourceService->loadTargetUser();

        $this->assertTrue($loadUserResult->hasError());
        $this->assertEquals($expectedError, $loadUserResult->getError());
    }
}