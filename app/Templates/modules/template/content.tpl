<div class="page-header">
	<h2>Dokumentasi <small>Tata Letak</small></h2>
</div>

<ul class="nav nav-pills">
	<li class="active"><a href="/template">Biasa</a></li>
	<li class="active"><a href="/template/left">Sisi Kiri</a></li>
	<li class="active"><a href="/template/right">Sisi Kanan</a></li>
	<li class="active"><a href="/template/both">Keduanya</a></li>
</ul>

<h3>Ulasan</h3>
<hr>

<p>Secara otomatis, <b>layout</b> akan mendeteksi keberadaan <code>block</code> dari setiap <b>template</b> yang akan digunakan.</p>
<br>

<h3>Utama</h3>
<hr>

<h4>Master Layout</h4>
<p>Setiap <b>template</b> akan memiliki tata letak sesuai <b>master layout</b> yang didefinisikan terlebih dahulu. Saat ini hanya mempunyai satu <b>master layout</b>.</p>
<pre>extends "layout.tpl"</pre>
<br>

<h4>Title</h4>
<pre>block title</pre>
<br>

<h4>Content</h4>
<pre>block content</pre>
<br>

<h4>Sidebar</h4>
<p>Dapat digunakan untuk membuat <b>sidebar</b> di sisi kiri. Jika tidak menggunakan <code>sidebar_left</code> dapat dihapus atau dikosongkan.</p>
<h5>Blok</h5>
<pre>block sidebar_left
block sidebar_right
</pre>
<br>
<h5>Penggunaan</h5>
<p>Dapat digunakan secara langsung di <b>template</b> atau dengan menggunakan <code>include</code> untuk mengambil <b>template</b> lainnya. Blok juga dapat mencetak variabel data secara langsung maupun ketika menggunakan <code>include</code>.</p>
<pre># Penggunaan secara langsung
block sidebar_left
    &lt;h1&gt;Contoh data&lt;/h1&gt;
endblock

# Penggunaan dengan include
block sidebar_right
    include "xxx/xxx.tpl"
endblock
</pre>
<br>


<h3>Tambahan</h3>
<hr>

<h4>Slider</h4>
<p>Menciptakan <b>slider</b> dengan mengambil gambar yang terdapat di folder <code>public/img</code>. <b>Slider</b> khusus digunakan untuk <code>ControllerHome</code>.</p>
<pre>block slider</pre>
<br>

<h4>Modules</h4>
<p>Menciptakan <b>modules</b>. <b>Modules</b> khusus digunakan untuk <code>ControllerHome</code>.</p>
<pre>block modules</pre>
<br>