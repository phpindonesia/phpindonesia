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
			
			<a href="/auth/loginfb" class="btn btn-large"><i class="icon-facebook-sign"></i> Masuk melalui Facebook</a>
			<hr>

			{% if result.error %}
			   <div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a>{{ result.error }}</div>
			{% endif %}
			<form method="POST" action="/auth/login">
				<input name="username" type="text" placeholder="Username/Email" class="span4" value="{{ postData.username }}">
				<input name="password" type="password" placeholder="Sandi" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Masuk</button> &nbsp;&nbsp;atau
				<a href="/auth/register" class="btn btn-link">Daftar</a><br/><br/>
				<a href="/auth/forgot" class="btn btn-link">Lupa sandi?</a>
			</form>
		</div>
	</div>

{% endblock %}