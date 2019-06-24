<?php
namespace App\Classes\Tools;

use App\Classes\Tools\ErrorResponseSituation;
use App\Classes\Error\Error;
use App\Classes\Error\BaseApiError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ErrorResponseCreator
{
    const HEADER = array(
        'Content-type'  => 'application/json; charset=utf-8',
        'Cache-Control' => 'no-cache, private'
    );

    private $internalError = null;
    private $responseControlGroups = array();
    private $temporaryErrorSituation = null;

    /**
     * ErrorResponseCreator constructor.
     *
     * @param Error $error
     */
    public function __construct(Error $error)
    {
        $this->temporaryErrorSituation = new ErrorResponseSituation();
        $this->setError($error);
    }

    /**
     * 設定錯誤
     *
     * @param Error $error
     * @return void
     */
    public function setError(Error $error)
    {
        $this->internalError = $error;
    }

    /**
     * 設定回應的錯誤代碼
     *
     * @param int $errorCode
     * @return ErrorResponseCreator
     */
    public function whenErrorIs(int $errorCode) : ErrorResponseCreator
    {
        $this->temporaryErrorSituation = new ErrorResponseSituation($errorCode);
        $this->responseControlGroups[] = $this->temporaryErrorSituation;

        return $this;
    }

    /**
     * 設定回應的狀態碼
     *
     * @param int $statusCode
     * @return ErrorResponseCreator
     */
    public function willRespondStatusCode(int $statusCode) : ErrorResponseCreator
    {
        $this->temporaryErrorSituation->setStatusCode($statusCode);

        return $this;
    }

    /**
     * 設定回應的Api錯誤物件
     *
     * @param BaseApiError $apiError
     * @return ErrorResponseCreator
     */
    public function willRespondApiError(BaseApiError $apiError) : ErrorResponseCreator
    {
        $this->temporaryErrorSituation->setApiError($apiError);

        return $this;
    }

    /**
     * 設定自訂義的回應錯誤訊息
     *
     * @param string $message
     * @return ErrorResponseCreator
     */
    public function willResponseCustomErrorMessage(string $message) : ErrorResponseCreator
    {
        $this->temporaryErrorSituation->setCustomErrorMessage($message);

        return $this;
    }

    /**
     * 建立回應
     *
     * @return JsonResponse
     */
    public function create() : JsonResponse
    {
        foreach ($this->responseControlGroups as $errorResponseSituation) {
            if ($errorResponseSituation->getErrorCode() == $this->internalError->getCode()) {
                $content = $errorResponseSituation->getApiError()->toResponseContent();

                $customMessage = $errorResponseSituation->getCustomErrorMessage();
                if ($customMessage != '') {
                    $content['error_message'] = $customMessage;
                }

                return new JsonResponse(
                    $content,
                    $errorResponseSituation->getStatusCode(),
                    self::HEADER,
                    JSON_UNESCAPED_UNICODE
                );
            }
        }

        return new JsonResponse(
            '未知的錯誤。',
            Response::HTTP_INTERNAL_SERVER_ERROR,
            self::HEADER,
            JSON_UNESCAPED_UNICODE
        );
    }
}
