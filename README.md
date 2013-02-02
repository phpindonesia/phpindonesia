README
======

[![Build Status](https://secure.travis-ci.org/phpindonesia/phpindonesia.png)](http://travis-ci.org/phpindonesia/phpindonesia) 

Official-repository untuk Portal PHP Indonesia. Project ini dikembangkan secara open-source oleh member PHP Indonesia.

Jika anda ingin berkontribusi, anda bisa membaca bagian [TATA CARA BERKONTRIBUSI](https://github.com/phpindonesia/phpindonesia/blob/master/CONTRIBUTING.md).

Requirements
------------

Untuk menjalankan aplikasi ini, anda membutuhkan web-server dan PHP versi 5.3

Instalasi
---------

Jika anda belum memiliki direktori yang berisi "Fork" atau "Clone" dari repositori ini, maka pertama-tama anda perlu clone repositori ini :

	git clone git://github.com/phpindonesia/phpindonesia.git

Sekarang masuk ke direktori utama aplikasi ini :
	
	cd phpindonesia

Aplikasi ini menggunakan komponen-komponen dari Composer, cara menginstall-nya :

	curl -s https://getcomposer.org/installer | php
	php composer.phar install

Setelah proses instalasi komponen-komponen tersebut selesai, anda akan menemukan folder **vendor** dalam direktori anda. Terakhir, anda perlu menambahkan **index.php** sebagai front-socket, yang berisi :

	<?php 

	include '/lokasi/folder/phpindonesia/app/bootstrap.php';

Letakkan **index.php** di luar direktori **phpindonesia**, dan arahkan base-directory dari virtual-host ke folder dimana file **index.php** berada.


Setelah itu anda harus menggenerate configurasi database terlebih dahulu dengan cara sebagai berikut:

 - Copy-Paste file dalam folder propel-template sejajar dengan folder vendor 
 - Ganti semua namanya dengan cara menghapus semua extension .tpl nya contoh: build.properties.tpl menjadi build.properties
 -  Buka file build.properties ganti line 16 - 18 sesuai dengan konfigurasi database anda. Line tersebut berisi : 
<pre>propel.database.url = mysql:host=localhost;dbname=dev_phpindonesia

propel.database.user = dev

propel.database.password = dev</pre>

 - Buka buildtime-conf.xml dan runtime-conf.xml dan ganti datasources default, id, dsn, user, dan password sesuai dengan konfigurasi database anda.
 - Buka console / terminal lalu masuk ke direktory utama aplikasi ini. Dari direktori utama ketik :
        
        chmod -cR 755 vendor/propel && vendor/bin/propel-gen diff && vendor/bin/propel-gen migrate && vendor/bin/propel-gen convert-conf


Jika semua-nya berjalan baik, saat ini seharusnya anda dapat menjalankan aplikasi PHP Indonesia. 

Menjalankan Tests
-----------------

Untuk menjalankan test-suite (dengan asumsi anda telah melakukan proses instalasi dengan benar) :

	cd /lokasi/folder/phpindonesia
	vendor/bin/phpunit --coverage-text
	
Proses pengembangan project ini menggunakan Continuous Integration dan Test Driven Development dengan Travis-CI untuk melakukan proses build secara otomatis (status build bisa dilihat di bagian paling atas dokumen ini).

**PHP Indonesia Team**