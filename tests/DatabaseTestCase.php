<?php
namespace Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseTestCase extends TestCase
{

    private $database = '';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = parent::createApplication();
        return $app;
    }

    /**
     * 解構
     *
     * @return void
     */
    public function __destruct()
    {
        if (file_exists($this->database)) {
            unlink($this->database);
        }
    }

    /**
     * 若資料庫不存在則初始化
     *
     * @return void
     */
    private function initializeTestingDatabaseIfNotExist()
    {
        // 確保測試資料庫存放的路徑有存在
        $path = Config::get('database.connections.sqlite_testing.path');
        if ( ! file_exists($path)) {
            mkdir($path, 0700, $recursive = true);
        }

        $preInitializedDatabase = sprintf('%sDatabaseTestCase.%s.sqlite', $path, getmypid());
        if (file_exists($preInitializedDatabase)) {
            return;
        }

        // 初始化測試用的 sqlite 檔案
        touch($preInitializedDatabase);
        Config::set('database.connections.sqlite_testing.database', $preInitializedDatabase);
        $this->artisan('migrate');
        $this->seed('DatabaseSeeder');
        DB::disconnect();
    }

    /**
     * 設定測試資料庫
     *
     * @return void
     */
    private function setTestingDatabase()
    {
        $path = Config::get('database.connections.sqlite_testing.path');
        $initializedDatabase = sprintf('%sDatabaseTestCase.%s.sqlite', $path, getmypid());
        // 以 microtime 作 hash 後取前六字元帶入檔名，避免同分秒執行測試時有檔名重複的情形。
        $microHash = substr(sha1(microtime()), 0, 6);
        $this->database = sprintf('%s%s.%s.%s.sqlite', $path, get_called_class(), time(), $microHash);
        copy($initializedDatabase, $this->database);
        Config::set('database.connections.sqlite_testing.database', $this->database);

        // 如果第一支測試案例執行`DB::beginTransaction()`會發生pdo連線不存在的錯誤
        // 加上重新連線可以確保pdo連線至最新設定的臨時資料庫
        DB::reconnect();
    }

    /**
     * 建立
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->initializeTestingDatabaseIfNotExist();
        $this->setTestingDatabase();
    }

    /**
     * 拆除
     *
     * @return void
     */
    public function tearDown() : void
    {
        parent::tearDown();
    }
}
