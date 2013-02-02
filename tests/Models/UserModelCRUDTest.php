<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserModelTest
 *
 * @author nurcahyo
 */
use app\Models\User;
use app\Models\UserPeer;
use app\Models\UserQuery;
use \Propel;

class UserModelCRUDTest extends PHPUnit_Framework_TestCase {

    const COLUMN_NAME_VALUE = 'testing123';

    static $userPk = '';
    static $created = 1359838641;

    public function setUp() {
        parent::setUp();
        Propel::init(dirname(__DIR__) . "/../app/conf/phpindonesia-conf.php");
    }

    public function testCreateUser() {
        $model = new User;
        $model->setName(self::COLUMN_NAME_VALUE);
        $attributes = array(
            "Mail" => 'testing@test.com',
            'Pass' => '12345678',
            'Created' => self::$created,
            'Access' => 0,
            'Data' => NULL,
            'Init' => 'testing@test.com',
            'Language' => 'id',
            'Login' => self::$created,
            'Picture' => 0,
            'Timezone' => null,
            'Status' => 1,
            'Signature' => ' ',
            'SignatureFormat' => NULL,
            'Theme' => 'none',
        );
        $model->fromArray($attributes);
        $this->assertNotEmpty($model->save());
        UserPeer::clearInstancePool();
    }

    public function testFailedCreateUser() {
        $model = new User;
        for ($i = 0; $i <= 15; ++$i) {
            $model->setByPosition($i, '1');
        }
        $model->setByName('Uid', 1);

        $this->setExpectedException('PropelException');
        $model->save();
        UserPeer::clearInstancePool();
    }

    public function testFindByColumnPositionUser() {
        $model = UserQuery::create()->findOneBy('Name', self::COLUMN_NAME_VALUE);
        $hasError = false;
        $n = 0;
        foreach ($model->toArray() as $value) {
            if ($model->getByPosition($n) != $value) {
                $hasError = true;
            }
            ++$n;
        }
        $this->assertFalse($hasError);
        UserPeer::clearInstancePool();
    }

    public function testFindUserByName() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        self::$userPk = $model->getPrimaryKey();
        $this->assertInstanceOf('\\app\\Models\\User', $model);
        UserPeer::clearInstancePool();
    }

    public function testIsNewRecords() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertFalse($model->isNew());
        UserPeer::clearInstancePool();
    }

    public function testClearAttributes() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $model->clear();
        $this->assertTrue($model->isNew());
        UserPeer::clearInstancePool();
    }

    public function testIsOnlyDefaultFalues() {
        $model = new User();
        $this->assertTrue($model->hasOnlyDefaultValues());
    }

    public function testReadUserAttribute() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertArrayHasKey('Name', $model->toArray());
        UserPeer::clearInstancePool();
    }

    public function testCopy() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $copy = $model->copy();
        $copy->setUid($model->getUid());
        $this->assertEquals($model->toArray()
                , $copy->toArray());
    }

    public function testReloadSuccess() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertNull($model->reload(true));
    }

    public function testReloadNewRecordFailed() {
        /* @var $model User */
        $model = new User;
        $this->setExpectedException('PropelException');
        $model->reload(true);
    }

    public function testReloadDeletedFailed() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $copy = $model->copy();
        $copy->setName('newNameUnique9999');
        $copy->save();
        $copy->delete();
        $this->setExpectedException('PropelException');
        $copy->reload();
    }

    public function testFindUserByPk() {
        $model = UserQuery::create()->findPk(self::$userPk);
        $this->assertInstanceOf('\\app\\Models\\User', $model);
        UserPeer::clearInstancePool();
    }

    public function testFindUsersByPk() {
        $models = UserQuery::create()->findPks(array(self::$userPk, 2));
        $this->assertInstanceOf('\\PropelObjectCollection', $models);
    }

    public function testUpdateUser() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $oldMail = $model->getMail();
        $model->setMail("renamed." . $oldMail);
        $model->save();
        UserPeer::clearInstancePool();
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertTrue(($model->getMail() != $oldMail));
        UserPeer::clearInstancePool();
    }

    public function testAttributeChangedUser() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $model->setMail("renamed." . $model->getMail());
        $model->setCreated(microtime());
        $this->assertEquals(array('phpid_users.MAIL', 'phpid_users.CREATED'), $model->getModifiedColumns());
        UserPeer::clearInstancePool();
    }

    public function testFalseInSaveState() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertFalse($model->isAlreadyInSave());
    }

    public function testFalseModifiedState() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertFalse($model->isModified());
    }

    public function testFalsePrimaryKeyNull() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        $this->assertFalse($model->isPrimaryKeyNull());
    }

    public function testDeleteUser() {
        /* @var $model User */
        $model = UserQuery::create()->findOneByName(self::COLUMN_NAME_VALUE);
        if ($model) {
            $model->delete();
        }
        $this->assertTrue($model->isDeleted());
        UserPeer::clearInstancePool();
    }

}

?>
