{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} 
	{% if isList == true %}
		{% include "blocks/sidebar/search.tpl" %} 
	{% else %}
		{% include "blocks/sidebar/profile.tpl" %} 
	{% endif %}
{% endblock %}

{% block content %} 
	{% if isList == true %}
		{% include "blocks/list/article.tpl" %} 
	{% else %}
		{% include "blocks/detail/article.tpl" %} 
	{% endif %}
{% endblock %}
