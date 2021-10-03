<?php

namespace App\Providers;

use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // 設定事件監聽使Database取出資料為陣列格式
        Event::listen(StatementPrepared::class, function ($event) {
            $statement = $event->statement;
        
        // 框架操作Job列表時 FETCH_MODE 必須為 FETCH_OBJ，故針對讀取Failed_Jobs或Jobs時不使用 FETCH_ASSOC 模式
        // https://github.com/laravel/framework/issues/23040
        // TODO:此方法為臨時處理方法，待後續找出更合適的處理方法
            if ( ! preg_match('/ FROM `?(failed_jobs|jobs)`?/i', $statement->queryString)) {
                $statement->setFetchMode(\PDO::FETCH_ASSOC);
            }
        });
    }
}
