<?php
namespace Tests\Classes;

use Tests\TestCase;
use App\Classes\DatabaseResource\User;

class UserTest extends TestCase
{
    /**
     * 測試setter and getter
     *
     * @return void
     */
    public function testSetterAndGetter()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('Jerry');
        $user->setEncryptedPassword('$2y$10$E0OoZlDLyaGyIReyP.tnIuhs96/txzMGpQcb6JmIWumf/THLg9Ih2');
        $user->setCreatedTimestamp(123456789);
        $user->setUpdatedTimestamp(123456789);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('Jerry', $user->getUsername());
        $this->assertEquals(123456789, $user->getCreatedTimestamp());
        $this->assertEquals(123456789, $user->getUpdatedTimestamp());
    }

    /**
     * 測試從陣列載入
     *
     * @return void
     */
    public function testLoadFromArray()
    {
        $content['ixUser'] = 99;
        $content['sUsername'] = 'Jerry';
        $content['sPassword'] = '$2y$10$E0OoZlDLyaGyIReyP.tnIuhs96/txzMGpQcb6JmIWumf/THLg9Ih2';
        $content['iCreatedTimestamp'] = 123456789;
        $content['iUpdatedTimestamp'] = 123456789;

        $user = new User();
        $user->loadFromArray($content);
        $this->assertEquals(99, $user->getId());
        $this->assertEquals('Jerry', $user->getUsername());
        $this->assertEquals(123456789, $user->getCreatedTimestamp());
        $this->assertEquals(123456789, $user->getUpdatedTimestamp());
    }

    /**
     * 測試檢查密碼是否正確
     *
     * @return void
     */
    public function testIsPasswordCorrect()
    {
        $user = new User();
        $user->setEncryptedPassword('$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou');
        $result = $user->isPasswordCorrect('123456789');
        $this->assertTrue($result);
    }

    /**
     * 測試檢查密碼是否正確
     *
     * @return void
     */
    public function testIsPasswordIfIncorrect()
    {
        $user = new User();
        $user->setEncryptedPassword('$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou');
        $result = $user->isPasswordCorrect('12345678');
        $this->assertFalse($result);
    }
}
