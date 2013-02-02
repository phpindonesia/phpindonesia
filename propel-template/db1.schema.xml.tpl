<?xml version="1.0" encoding="UTF-8"?>
<database name="dev_phpindonesia" defaultIdMethod="native" tablePrefix="phpid_">
    <!-- table definitions go here -->
    <table name="users" description="User Table" namespace="\app\Models" phpName="User">
        <column name="uid" required="true" type="INTEGER" size="10" primaryKey="true" autoIncrement="true" description="Unique user ID." />
        <column name="names" required="true" type="VARCHAR" size="60"  description="Unique user name." />
        <column name="pass" type="VARCHAR" size="128" required="true" defaultValue="" description="User’s password (hashed)." />
        <column name="mail" type="VARCHAR" size="320" defaultValue="" />
        <column name="theme" type="VARCHAR" size="255" required="true" defaultValue=""  description="User’s default theme." />
        <column name="signature" type="VARCHAR" size="255" required="true" defaultValue=""  description="User’s signature." />
        <column name="signature_format" type="VARCHAR" size="255" defaultValue="NULL" description="The phpid_filter_format.format of the signature." />
        <column name="created" type="INTEGER" size="11" required="true" defaultValue="0" description="Timestamp for when user was created." />
        <column name="access" type="INTEGER" size="11" required="true" defaultValue="0" description="Timestamp for previous time user accessed the site." />
        <column name="login" type="INTEGER" size="11" required="true" defaultValue="0" description="Timestamp for user’s last login." />
        <column name="status" type="TINYINT" size="4" required="true" defaultValue="0" description="Whether the user is active(1) or blocked(0)." />
        <column name="timezone" type="VARCHAR" size="32" defaultValue="NULL" description="User’s time zone." />
        <column name="language" type="VARCHAR" size="12" required="true" defaultValue="" description="User’s default language." />
        <column name="picture" type="INTEGER" size="11" required="true" defaultValue="0" description="Foreign key: phpid_file_managed.fid of user’s picture." />
        <column name="init" type="VARCHAR" size="254" defaultValue=""  description="E-mail address used for initial account creation." />
        <column name="data" type="LONGVARBINARY" description="A serialized array of name value pairs that are related to the user. Any form values posted during user edit are stored and are loaded into the $user object during user_load(). Use of this field is discouraged and it will likely disappear in a future..." />
        <!--indicies-->
        <unique name="name">
            <unique-column name="name" />
        </unique>
        <index name="access">
            <index-column name="access" />
        </index>
        <index name="created">
            <index-column name="created" />
        </index>
        <index name="mail">
            <index-column name="mail" size="255" />
        </index>
        <index name="picture">
            <index-column name="picture" />
        </index>
    </table>
</database>
