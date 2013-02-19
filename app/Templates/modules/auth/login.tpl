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

			<a href="/" class="btn btn-large"><i class="icon-facebook-sign"></i> Masuk melalui Facebook</a>
			<hr>

			<form>
				<input type="text" placeholder="Email" class="span4">
				<input type="password" placeholder="Sandi" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Masuk</button> 
				<a href="/auth/forgot" class="btn btn-link">Lupa sandi?</a>
			</form>
			<hr>

			<p>Belum punya akun? <a href="/auth/register">Daftar</a></p>
		</div>
	</div>

{% endblock %}