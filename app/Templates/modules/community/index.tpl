{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} 
	{% include "blocks/sidebar/search.tpl" %} 
	<hr/>
	<h4>Tulisan Terbaru</h4>
	{% include "blocks/sidebar/preview/article.tpl" %} 
	<a href="/community/article"><i class="icon icon-th-list"></i> Lihat semua tulisan</a>
{% endblock %}

{% block content %} 
	{% include "blocks/list/user.tpl" %} 
{% endblock %}