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

Setelah proses instalasi komponen-komponen tersebut selesai, anda akan menemukan folder **vendor** dalam direktori anda. 

Selanjutnya, kita akan menyiapkan database menggunakan **Propel ORM**. Pertama tama, buatlah database dengan nama **phpindonesia** beserta user yang memiliki hak akses ke database tersebut. Kini anda perlu membuat 3 file :

- build.properties. Berisi global variable yang diperlukan Propel saat run-time
- connection.xml. Berisi konfigurasi database yang diperlukan untuk membuat koneksi.
- buildtime.xml. Berisi konfigurasi database yang diperlukan untuk proses migrasi.

Anda dapat menggunakan template (build.properties.tpl, connection.xml.tpl, buildtime.xml.tpl) sebagai starting point dan mengubah nilai-nya (nama database, username, password) sesuai dengan environment anda. Di sistem UNIX, mungkin anda akan perlu mengubah permission pada propel agar script **propel-gen** berjalan :
	
	chmod -R 755 vendor/proper

Setelah ke-tiga file tersebut siap, sekarang kita bisa menjalankan :

	vendor/bin/propel-gen . diff migrate
	vendor/bin/propel-gen -quiet

Jika semua berjalan lancar, database beserta file-file yang diperlukan akan di-generate oleh proses tersebut. Lihat [Dokumentasi Propel](http://propelorm.org/documentation/) jika anda memerlukan panduan lebih lanjut.

Terakhir, anda perlu menambahkan **index.php** sebagai front-socket, yang berisi :

	<?php 

	include '/lokasi/folder/phpindonesia/app/bootstrap.php';

Letakkan **index.php** di luar direktori **phpindonesia**, dan arahkan base-directory dari virtual-host ke folder dimana file **index.php** berada. Jika semua-nya berjalan baik, saat ini seharusnya anda dapat menjalankan aplikasi PHP Indonesia. 

Menjalankan Tests
-----------------

Untuk menjalankan test-suite (dengan asumsi anda telah melakukan proses instalasi dengan benar) :

	cd /lokasi/folder/phpindonesia
	vendor/bin/phpunit --coverage-text
	
Proses pengembangan project ini menggunakan Continuous Integration dan Test Driven Development dengan Travis-CI untuk melakukan proses build secara otomatis (status build bisa dilihat di bagian paling atas dokumen ini).

Menggunakan Propel Database Migration Tools
--------------------------------------------
Silakan baca http://propelorm.org/documentation/10-migrations.html untuk mengetahui cara menggunakan migration tools.

**PHP Indonesia Team**
