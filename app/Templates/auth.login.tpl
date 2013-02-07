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
				<input type="text" placeholder="Email" class="span4">
				<input type="password" placeholder="Sandi" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Masuk</button> 
				<a href="/auth/register" class="btn btn-link">Belum terdaftar?</a>
			</form>

		</div>
	</div>

{% endblock %}