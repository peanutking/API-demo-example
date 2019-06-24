<?php
namespace App\Classes\Tools;

use App\Classes\Error\Error;
use App\Classes\Error\BaseApiError;

class ErrorResponseSituation
{
    private $errorCode = 0;
    private $apiError = null;
    private $customErrorMessage = '';
    private $responseStatusCode = 0;

    /**
     * ErrorResponseSituation constructor.
     *
     * @param int $errorCode
     */
    public function __construct(int $errorCode = Error::NO_ERROR)
    {
        $this->setErrorCode($errorCode);
    }

    /**
     * 設定錯誤代碼
     *
     * @param int $errorCode
     * @return void
     */
    private function setErrorCode(int $errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * 設定Api錯誤物件
     *
     * @param BaseApiError $apiError
     * @return void
     */
    public function setApiError(BaseApiError $apiError)
    {
        $this->apiError = $apiError;
    }

    /**
     * 設定自定義的錯誤訊息
     *
     * @param string $errorMessage
     * @return void
     */
    public function setCustomErrorMessage(string $errorMessage)
    {
        $this->customErrorMessage = $errorMessage;
    }

    /**
     * 設定狀態碼
     *
     * @param $statusCode
     * @return void
     */
    public function setStatusCode(int $statusCode)
    {
        $this->responseStatusCode = $statusCode;
    }

    /**
     * 取得錯誤
     *
     * @return int
     */
    public function getErrorCode() : int
    {
        return $this->errorCode;
    }

    /**
     * 取得Api錯誤物件
     *
     * @return BaseApiError
     */
    public function getApiError() : BaseApiError
    {
        return $this->apiError;
    }

    /**
     * 取得自定義的錯誤訊息
     *
     * @return null|string
     */
    public function getCustomErrorMessage()
    {
        return $this->customErrorMessage;
    }

    /**
     * 取得狀態碼
     *
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->responseStatusCode;
    }
}
