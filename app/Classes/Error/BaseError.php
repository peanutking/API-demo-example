<?php
namespace App\Classes\Error;

abstract class BaseError
{
    const NO_ERROR = 0;

    protected $code = 0;
    protected $message = '';
    protected $baseErrorMessage = array(
        self::NO_ERROR => '没有错误'
    );

    protected $errorMessage = array();

    /**
     * BaseError constructor.
     *
     * @param int $code
     * @param string $customMessage
     */
    public function __construct(int $code = BaseError::NO_ERROR, string $customMessage = '')
    {
        $this->baseErrorMessage = $this->baseErrorMessage + $this->errorMessage;

        $this->setCode($code);
        // 如有自定義message覆寫 message
        if ($customMessage != '') {
            $this->message = $customMessage;
        }
    }

    /**
     * 設定錯誤編號
     *
     * @param int $code
     * @return void
     */
    public function setCode(int $code)
    {
        if (isset($this->baseErrorMessage[$code])) {
            $this->code = $code;
            $this->message = $this->baseErrorMessage[$code];
        }
    }

    /**
     * 取得錯誤編號
     *
     * @return int
     */
    public function getCode() : int
    {
        return $this->code;
    }

    /**
     * 取得錯誤訊息
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }
}