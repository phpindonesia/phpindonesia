{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block content %} 

	<div class="row">
		<div class="span6 offset3">

			<div class="alert alert-error">
				<h3>{{ title }}</h3>
				<p>{{ content.message }}</p>
				<button class="btn" onClick="javascript:window.history.back();">Kembali</button>
			</div>


		</div>
	</div>

{% endblock %}