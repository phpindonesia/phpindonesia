{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block content %} 

	<div class="row">
		<div class="span4 offset4">

			<h3>{{title}}</h3>
			<hr>

			<a href="/" class="btn btn-large"><i class="icon-facebook-sign"></i> Daftar dengan Facebook</a>
			<hr>

			<form>
				<input type="text" placeholder="Nama Lengkap" class="span4">
				<input type="text" placeholder="Email" class="span4">
				<input type="text" placeholder="Nama Pengguna" class="span4">
				<input type="password" placeholder="Sandi" class="span4">
				<input type="password" placeholder="Konfirmasi Sandi" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Daftar</button> 
				<a href="/auth/login" class="btn btn-link">Sudah punya akun?</a>
			</form>

		</div>
	</div>

{% endblock %}