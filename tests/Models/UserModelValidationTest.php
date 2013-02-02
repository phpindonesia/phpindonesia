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

class UserModelValidationTest extends PHPUnit_Framework_TestCase {

    const COLUMN_NAME_VALUE = 'testing123';

    static $userPk = '';

    public function setUp() {
        parent::setUp();
        Propel::init(dirname(__DIR__) . "/../app/conf/phpindonesia-conf.php");
    }

    public function testValidateUserSuccess() {
        $model = new User;
        $model->setName(self::COLUMN_NAME_VALUE);
        $model->setMail('testing@test.com');
        $model->setPass('12345678');
        $model->setInit($model->getMail());
        $this->assertTrue($model->validate());
        UserPeer::clearInstancePool();
    }

    public function testValidateUserFailed() {
        $model = new User;
        $model->setName('test');
        $model->setMail('testing@test.com');
        $model->setPass('12345678');
        $model->setInit($model->getMail());
        $this->assertFalse($model->validate());
        UserPeer::clearInstancePool();
    }

    public function testValidateUserFailureMessage() {
        $model = new User;
        $model->setName('test');
        $model->setMail('testing@test.com');
        $model->setPass('12345678');
        $model->setInit($model->getMail());
        $model->validate();
        $this->assertArrayHasKey('phpid_users.NAME', $model->getValidationFailures());
        UserPeer::clearInstancePool();
    }

}

?>
