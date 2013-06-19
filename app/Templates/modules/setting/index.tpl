{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} {% include "blocks/sidebar/menu.tpl" %} {% endblock %}
{% block content %} 
	{% if content is not empty %}
	<h3>{{ content.title }}</h3>
	<hr>

	{% if content.error %}
	   <div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a>{{ content.error|raw }}</div>
	{% endif %}
	<form method="POST" action="{{ currentUrl }}">
		{% for input in content.inputs %}
			{% if input.type == "text" or input.type == "password" %}
			<p><input name="{{ input.name }}" type="{{ input.type }}" placeholder="{{ input.placeholder }}" class="span{{ input.size }}" value="{{ input.value }}"></p>
			{% elseif input.type == "textarea" %}
			<p><textarea name="{{ input.name }}" placeholder="{{ input.placeholder }}" class="span{{ input.size }}">{{ input.value }}</textarea></p>
			{% endif %}
		{% endfor %}
		<hr>
		<button type="submit" class="btn btn-primary">Update</button>
	</form>
	{% else %}
	<div class="alert alert-info"><center>Pilih menu di sidebar kiri untuk melakukan penyetelan</center></div>
	{% endif %}
{% endblock %}