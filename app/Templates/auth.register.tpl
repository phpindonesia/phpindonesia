{######################## MASTER ########################}
{% extends "master/single.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block content %} 

	<div class="row">
		<div class="span4 offset4">

			<h3>{{title}}</h3>
			<hr>

			<form>
				<input type="text" placeholder="Nama Lengkap" class="span4">
				<input type="text" placeholder="Email" class="span4">
				<input type="text" placeholder="Nama Pengguna" class="span4">
				<input type="password" placeholder="Sandi" class="span4">
				<input type="password" placeholder="Konfirmasi Sandi" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Daftar</button> 
				<a href="/auth/forgot" class="btn btn-link">Lupa sandi?</a>
			</form>

		</div>
	</div>

{% endblock %}