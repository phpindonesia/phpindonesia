<?xml version="1.0" encoding="UTF-8"?>
<config>
  <propel>
    <datasources default="phpindonesia">
      <datasource id="phpindonesia">
        <adapter>mysql</adapter>
        <connection>
          <!-- Ubah nilai di bawah ini sesuai dengan environment anda -->
          <dsn>mysql:host=localhost;dbname=phpindonesia</dsn>
          <user>travis</user>
          <password></password>
        </connection>
      </datasource>
    </datasources>
  </propel>
</config>