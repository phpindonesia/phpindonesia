<?php

namespace app\Models\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'phpid_users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.app.Models.map
 */
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'app.Models.map.UserTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('phpid_users');
        $this->setPhpName('User');
        $this->setClassname('app\\Models\\User');
        $this->setPackage('app.Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('UID', 'Uid', 'INTEGER', true, 10, null);
        $this->addColumn('NAMES', 'Names', 'VARCHAR', true, 60, null);
        $this->addColumn('PASS', 'Pass', 'VARCHAR', true, 128, '');
        $this->addColumn('MAIL', 'Mail', 'VARCHAR', false, 320, '');
        $this->addColumn('THEME', 'Theme', 'VARCHAR', true, 255, '');
        $this->addColumn('SIGNATURE', 'Signature', 'VARCHAR', true, 255, '');
        $this->addColumn('SIGNATURE_FORMAT', 'SignatureFormat', 'VARCHAR', false, 255, null);
        $this->addColumn('CREATED', 'Created', 'INTEGER', true, 11, 0);
        $this->addColumn('ACCESS', 'Access', 'INTEGER', true, 11, 0);
        $this->addColumn('LOGIN', 'Login', 'INTEGER', true, 11, 0);
        $this->addColumn('STATUS', 'Status', 'TINYINT', true, 4, 0);
        $this->addColumn('TIMEZONE', 'Timezone', 'VARCHAR', false, 32, null);
        $this->addColumn('LANGUAGE', 'Language', 'VARCHAR', true, 12, '');
        $this->addColumn('PICTURE', 'Picture', 'INTEGER', true, 11, 0);
        $this->addColumn('INIT', 'Init', 'VARCHAR', false, 254, '');
        $this->addColumn('DATA', 'Data', 'LONGVARBINARY', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // UserTableMap
