<?php
namespace Tests\Http\Controllers;

use App\Classes\DatabaseResource\User;
use App\Classes\Error\ApiError;
use App\Classes\Error\Error;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnBoolean;
use App\Classes\ErrorHandleableReturns\ErrorHandleableReturnDatabaseResource;
use App\Models\UserModel;
use Illuminate\Http\Response;
use Tests\DatabaseTestCase;
use Mockery;

class UserControllerTest extends DatabaseTestCase
{
    /**
     * 測試新增用戶
     *
     * @return void
     */
    public function testAddUser()
    {
        $parameters = array();
        $parameters['username'] = 'Cool';
        $parameters['password'] = 'a12345678';
        $response = $this->post('/api/users', $parameters);

        $response->assertStatus(Response::HTTP_CREATED);
    }

     /**
     * 測試新增用戶檢查用戶名稱是否存在時，如果用戶名稱已存在
     *
     * @return void
     */
    public function testAddUserIfUserNameExist()
    {
        $expectedContent = (new ApiError(ApiError::USERNAME_EXIST))->toResponseContent();
        $parameters = array();
        $parameters['username'] = 'John';
        $parameters['password'] = 'a12345678';

        $response = $this->post('/api/users', $parameters);
        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertExactJson($expectedContent);
    }

    /**
     * 測試新增用戶檢查用戶名稱是否存在時，如果發生資料庫錯誤
     *
     * @return void
     */
    public function testAddUserIfDatabaseErrorOccurredWhenGetByUserName()
    {
        $expectedContent = (new ApiError(ApiError::INTERNAL_SERVER_ERROR))->toResponseContent();
        $parameters = array();
        $parameters['username'] = 'Cool';
        $parameters['password'] = 'a12345678';

        $mockUserModel = Mockery::instanceMock(UserModel::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $mockUserModel->shouldReceive('getByUsername')
            ->once()
            ->andReturn(new ErrorHandleableReturnDatabaseResource(new User(), new Error(Error::DATABASE_ERROR)));
        $this->app->instance(UserModel::class, $mockUserModel);

        $response = $this->post('/api/users', $parameters);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertExactJson($expectedContent);
    }

    /**
     * 測試新增用戶時，如果發生資料庫錯誤
     *
     * @return void
     */
    public function testAddUserIfDatabaseErrorOccurred()
    {
        $expectedContent = (new ApiError(ApiError::INTERNAL_SERVER_ERROR))->toResponseContent();
        $parameters = array();
        $parameters['username'] = 'Cool';
        $parameters['password'] = 'a12345678';

        $mockUserModel = Mockery::instanceMock(UserModel::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $mockUserModel->shouldReceive('insertNewUser')
            ->once()
            ->andReturn(new ErrorHandleableReturnBoolean(false, new Error(Error::DATABASE_ERROR)));
        $this->app->instance(UserModel::class, $mockUserModel);

        $response = $this->post('/api/users', $parameters);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertExactJson($expectedContent);
    }
}