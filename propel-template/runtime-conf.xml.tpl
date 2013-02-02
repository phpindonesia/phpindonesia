<?xml version="1.0"?>
<config>
<!--    <log>
        <ident>PhpIndonesia</ident>
        <type>console</type>
        <level>7</level>
    </log>-->
    <propel>
        <datasources default="dev_phpindonesia">
            <datasource id="dev_phpindonesia">
                <adapter>mysql</adapter>
                <connection>
                    <classname>DebugPDO</classname>
                    <dsn>mysql:host=localhost;dbname=dev_phpindonesia</dsn>
                    <user>travis</user>
                    <password></password>
                    <!--                    <options>
                        <option id="ATTR_PERSISTENT">false</option>
                    </options>-->
                    <attributes>
                        <option id="ATTR_EMULATE_PREPARES">true</option>
                    </attributes>
                    <settings>
                        <setting id="charset">utf8</setting>
                        <!--                        <setting id="queries">
                            <query>set search_path myschema, public</query> automatically set postgresql's search_path 
                            <query>INSERT INTO BAR ('hey', 'there')</query> execute some other query 
                        </setting>-->
                    </settings>
                </connection>
                <!--                <slaves>
                    <connection>
                        <dsn>mysql:host=slave-server1; dbname=bookstore</dsn>
                    </connection>
                    <connection>
                        <dsn>mysql:host=slave-server2; dbname=bookstore</dsn>
                    </connection>
                </slaves>-->
            </datasource>
        </datasources>
        <debugpdo>
            <logging>
                <details>
                    <method>
                        <enabled>true</enabled>
                    </method>
                    <time>
                        <enabled>true</enabled>
                        <precision>3</precision>
                    </time>
                    <mem>
                        <enabled>true</enabled>
                        <precision>1</precision>
                    </mem>
                </details>
            </logging>
        </debugpdo>
    </propel>
</config>
