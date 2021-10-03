<?php
namespace App\Classes\Tools;

use App\Classes\Error\Error;
use App\Classes\Error\BaseApiError;

class ErrorResponseSituation
{
    private $errorCodes = array();
    private $apiError = null;
    private $customErrorMessage = '';
    private $responseStatusCode = 0;

    /**
     * ErrorResponseSituation constructor.
     *
     * @param array $errorCode
     */
    public function __construct(array $errorCodes = array(Error::NO_ERROR))
    {
        $this->setErrorCode($errorCodes);
    }

    /**
     * 設定錯誤代碼
     *
     * @param array $errorCodes
     * @return void
     */
    private function setErrorCode(array $errorCodes)
    {
        $this->errorCodes = $errorCodes;
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
     * @return array
     */
    public function getErrorCodes() : array
    {
        return $this->errorCodes;
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
