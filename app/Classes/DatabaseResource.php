<?php
namespace App\Classes;

interface DatabaseResource
{
    /**
     * 設定編號
     *
     * @param  int $id
     * @return void
     */
    public function setId(int $id);

    /**
     * 取得編號
     *
     * @return int
     */
    public function getId() : int;

    /**
     * 從陣列匯入資料
     *
     * @param  array $content
     * @return void
     */
    public function loadFromArray(array $content);
}