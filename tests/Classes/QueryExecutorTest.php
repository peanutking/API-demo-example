<?php
namespace Tests\Classes;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\DatabaseTestCase;
use PDOException;
use Mockery;
use Illuminate\Support\Facades\DB;
use App\Classes\QueryExecutor;
use App\Classes\Error;

class QueryExecutorTest extends DatabaseTestCase
{
    /**
     * 關閉Mockery
     *
     * @return void
     */
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * 測試新增成功
     *
     * @return void
     */
    public function testSuccessInsert()
    {
        $sqlQuery = "
            INSERT INTO `user`
             (`sUsername`, `sPassword`, `iCreatedTimestamp`)
            VALUES
             ('Peter', 'bYcFpyWTfT6D3WxAyvCAMw3YzBnFhby62ABZK9JBDs5hdsHBLaMqgEYtRXXL', 1513098301)";
        $handler = QueryExecutor::insert($sqlQuery);

        $this->assertTrue($handler->getValue());
        $this->assertFalse($handler->hasError());
    }

    /**
     * 測試新增失敗，如果過程中拋出PDOException
     *
     * @return void
     */
    public function testFailedInsertIfThrowPDOException()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        DB::shouldReceive('insert')
            ->once()
            ->andThrow('PDOException');

        $sqlQuery = "
        INSERT INTO `user`
         ( `sUsername`, `sPassword`, `iCreatedTimestamp`)
        VALUES
         ('Peter', 'bYcFpyWTfT6D3WxAyvCAMw3YzBnFhby62ABZK9JBDs5hdsHBLaMqgEYtRXXL', 1513098301)";
        $handler = QueryExecutor::insert($sqlQuery);

        $this->assertFalse($handler->getValue());
        $this->assertTrue($handler->hasError());
        $this->assertEquals($expectedError, $handler->getError());
    }

    /**
     * 測試搜尋成功
     *
     * @return void
     */
    public function testSuccessSelect()
    {
        $expected = array();
        $expected['ixUser'] = '1';
        $expected['sUsername'] = 'Alex';
        $expected['sPassword'] = '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou';
        $expected['iCreatedTimestamp'] = '123456789';
        $expected['iUpdatedTimestamp'] = null;

        $sqlQuery = "
            SELECT *
            FROM   `user`
            WHERE  `ixUser` = 1";
        $returnValue = QueryExecutor::select($sqlQuery);
        $selectResult = $returnValue->getValue();

        $this->assertFalse($returnValue->hasError());
        $this->assertEquals($expected, $selectResult[0]);
    }

    /**
     * 測試搜尋失敗，如果過程中拋出PDOException
     *
     * @return void
     */
    public function testFailedSelectIfThrowPDOException()
    {
        $expectedError = new Error(Error::DATABASE_ERROR);

        DB::shouldReceive('select')
            ->once()
            ->andThrow('PDOException');

        $sqlQuery = "
            SELECT *
            FROM   `user`
            WHERE  `ixUser` = 1";
        $returnValue = QueryExecutor::select($sqlQuery);
        $selectResult = $returnValue->getValue();

        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedError, $returnValue->getError());
        $this->assertEmpty($selectResult);
    }

    /**
     * 測試更新成功
     *
     * @return void
     */
    public function testSuccessUpdate()
    {
        $expectedValue = 1;

        $sqlQuery = sprintf("
            UPDATE `user`
            SET    `iUpdatedTimestamp` = %u
            WHERE  `ixUser` = 1"
            , time());
        $returnValue = QueryExecutor::update($sqlQuery);

        $this->assertFalse($returnValue->hasError());
        $this->assertEquals($expectedValue, $returnValue->getValue());
    }

    /**
     * 測試刪除成功
     *
     * @return void
     */
    public function testSuccessDelete()
    {
        $expectedValue = 1;

        $sqlQuery = "
            DELETE FROM `user`
            WHERE       `ixUser` = 1";
        $returnValue = QueryExecutor::delete($sqlQuery);

        $this->assertFalse($returnValue->hasError());
        $this->assertEquals($expectedValue, $returnValue->getValue());
    }

    /**
     * 測試刪除失敗，如果過程中拋出PDOException
     *
     * @return void
     */
    public function testFailedDeleteIfThrowPDOException()
    {
        $expectedValue = 0;
        $expectedError = new Error(Error::DATABASE_ERROR);

        DB::shouldReceive('delete')
            ->once()
            ->andThrow('PDOException');

            $sqlQuery = "
                DELETE FROM `user`
                WHERE       `ixUser` = 1";
        $returnValue = QueryExecutor::delete($sqlQuery);

        $this->assertEquals($expectedValue, $returnValue->getValue());
        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedError, $returnValue->getError());
    }

    /**
     * 測試取得最新一筆Id
     *
     * @return void
     */
    public function testGetLastInsertId()
    {
        $expectedId = 99;

        $sqlQuery = sprintf("
            INSERT INTO `user`
            (`ixUser`, `sUsername`, `sPassword`, `iCreatedTimestamp`)
            VALUES
            (%u, 'Peter', 'bYcFpyWTfT6D3WxAyvCAMw3YzBnFhby62ABZK9JBDs5hdsHBLaMqgEYtRXXL', 1513098301)"
            , (int)$expectedId);
        $insertResult = QueryExecutor::insert($sqlQuery);

        $returnValue = QueryExecutor::getLastInsertId();

        $this->assertFalse($returnValue->hasError());
        $this->assertEquals($expectedId, $returnValue->getValue());
    }

    /**
     * 測試取得最新一筆Id，如果過程中拋出PDOException
     *
     * @return void
     */
    public function testGetLastInsertIdIfThrowException()
    {
        $expectedId = 0;
        $expectedError = new Error(Error::FORBIDDEN);

        $pdo = $this->getMockBuilder('PDO')
            ->disableOriginalConstructor()
            ->setMethods(array('lastInsertId'))
            ->getMock();

        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willThrowException(new PDOException());

        DB::shouldReceive('getPdo')
            ->once()
            ->andReturn($pdo);

        $returnValue = QueryExecutor::getLastInsertId();

        $this->assertTrue($returnValue->hasError());
        $this->assertEquals($expectedId, $returnValue->getValue());
        $this->assertEquals($expectedError, $returnValue->getError());
    }
}