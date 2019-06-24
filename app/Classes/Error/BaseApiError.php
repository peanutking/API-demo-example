<?php
namespace App\Classes\Error;

use App\Classes\Error\BaseError;

abstract class BaseApiError extends BaseError
{
    /**
     * 匯出為回應內容
     *
     * @return array
     */
    public function toResponseContent() : array
    {
        $content = array();
        $content['error_code'] = $this->getCode();
        $content['error_message'] = $this->getMessage();

        return $content;
    }
}
