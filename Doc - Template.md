# Dokumentasi - Tata Letak

## Ulasan

Secara otomatis, **layout** akan mendeteksi keberadaan `block` dari setiap **template** yang akan digunakan.


## Utama

### Master Layout

Setiap **template** akan memiliki tata letak sesuai **master layout** yang didefinisikan terlebih dahulu. Saat ini hanya mempunyai satu **master layout**.

	extends "layout.tpl"

### Title

	block title

### Content

	block content

### Sidebar

Dapat digunakan untuk membuat **sidebar** di sisi kiri. Jika tidak menggunakan `sidebar_left` dapat dihapus atau dikosongkan.

#### Blok

	block	 sidebar_left
	block sidebar_right

#### Penggunaan

Dapat digunakan secara langsung di template atau dengan menggunakan include untuk mengambil template lainnya. Blok juga dapat mencetak variabel data secara langsung maupun ketika menggunakan include.

	# Penggunaan secara langsung
	block sidebar_left
	    <h1>Contoh data</h1>
	endblock
	
	# Penggunaan dengan include
	block sidebar_right
	    include "xxx/xxx.tpl"
	endblock

## Tambahan

### Slider

Menciptakan slider dengan mengambil gambar yang terdapat di folder public/img. Slider khusus digunakan untuk ControllerHome.

	block slider

### Modules

Menciptakan modules. Modules khusus digunakan untuk ControllerHome.

	block modules

## Tampilan

### Biasa
![Biasa](https://dl.dropbox.com/u/83581209/phpindonesia/etc/1.png)

### Sisi Kiri
![Biasa](https://dl.dropbox.com/u/83581209/phpindonesia/etc/2.png)

### Sisi Kanan
![Biasa](https://dl.dropbox.com/u/83581209/phpindonesia/etc/3.png)

### Keduanya
![Biasa](https://dl.dropbox.com/u/83581209/phpindonesia/etc/4.png)