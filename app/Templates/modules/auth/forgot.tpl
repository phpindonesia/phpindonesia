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

			{% if result.error %}
			   <div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a>{{ result.error }}</div>
			{% endif %}
			<form method="POST" action="/auth/forgot">
				<input name="email" type="text" placeholder="Email anda" class="span4">

				<hr>

				<button type="submit" class="btn btn-primary">Kirim permintaan</button>
			</form>

		</div>
	</div>

{% endblock %}