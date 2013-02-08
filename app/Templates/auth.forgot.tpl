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

				<hr>

				<button type="submit" class="btn btn-primary">Kirim permintaan</button>
			</form>

		</div>
	</div>

{% endblock %}