<?php

namespace App\Http\Controllers;

use App\Classes\DatabaseResource\User;
use App\Classes\Error\Error;
use App\Classes\Error\ApiError;
use App\Classes\Tools\ResponseCreator;
use App\Classes\Tools\ErrorResponseCreator;
use App\Models\UserModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Log;

class UserController extends Controller
{
    /**
     * 新增使用者
     *
     * URI: POST /web/users
     *
     * @return JsonResponse
     */
    public function addUser() : JsonResponse
    {
        //TODO: 後續補上參數檢查

        $username = Request::get('username');
        $password = Request::get('password');

        $userModel = resolve(UserModel::class);
        $user = new User();
        $user->setUsername($username);
        $hashPassword = Hash::make($password);

        $isUserNameExistResult = $userModel->getByUsername($username);
        if ($isUserNameExistResult->getError()->getCode() != Error::RESOURCE_NOT_FOUND) {
            if ($isUserNameExistResult->getError()->getCode() == Error::DATABASE_ERROR) {
                return ErrorResponseCreator::createErrorResponse(
                    new ApiError(ApiError::INTERNAL_SERVER_ERROR),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
            return ErrorResponseCreator::createErrorResponse(
                new ApiError(ApiError::USERNAME_EXIST),
                Response::HTTP_CONFLICT
            );
        }

        $addUserResult = $userModel->insertNewUser($user, $hashPassword);
        log::info($addUserResult->getValue());
        if ($addUserResult->hasError()) {
            $responseCreator = new ErrorResponseCreator($addUserResult->getError());
            return $responseCreator
                ->whenErrorIn(Error::DATABASE_ERROR, Error::RESOURCE_NOT_BUILD)
                    ->willRespondApiError(new ApiError(ApiError::INTERNAL_SERVER_ERROR))
                    ->willRespondStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->create();
        }

        return ResponseCreator::createResponse(array(), Response::HTTP_CREATED);
    }
}