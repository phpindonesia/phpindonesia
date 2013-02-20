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

			{% if acl.isContainFacebookData == false %}
			<a href="/auth/registerfb" class="btn btn-large"><i class="icon-facebook-sign"></i> Daftar dengan Facebook</a>
			<hr>
			{% endif %}

			{% if result.error %}
			   <div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a>{{ result.error }}</div>
			{% endif %}
			<form method="POST" action="/auth/register">
				<input name="username" type="text" placeholder="Username" class="span4" value="{{ postData.username }}">
				<input name="email" type="text" placeholder="Email" class="span4" value="{{ postData.email }}">
				<input name="password" type="password" placeholder="Sandi" class="span4">
				<input name="cpassword" type="password" placeholder="Konfirmasi Sandi" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Daftar</button> 
				<a href="/auth/login" class="btn btn-link">Sudah punya akun?</a>
			</form>

		</div>
	</div>

{% endblock %}