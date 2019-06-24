<?php
namespace Tests\Classes\Tools;

use App\Classes\Tools\SqlQueryHelper;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SqlQueryHelperTest extends TestCase
{
    /**
     * 測試取得當前資料庫時間的語法
     *
     * @return void
     */
    public function testGetSyntaxOfCurrentSqlTimestamp()
    {
        $expectedSqliteSyntax = "STRFTIME('%s','NOW')";

        $syntax = SqlQueryHelper::getSyntaxOfCurrentSqlTimestamp();
        $this->assertEquals($expectedSqliteSyntax, $syntax);

        Config::set('database.default', 'mysql');

        $expectedMysqlSyntax = 'UNIX_TIMESTAMP(CURRENT_TIMESTAMP)';
        $syntax = SqlQueryHelper::getSyntaxOfCurrentSqlTimestamp();

        $this->assertEquals($expectedMysqlSyntax, $syntax);
    }
}