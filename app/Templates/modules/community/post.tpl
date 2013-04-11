{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} 
	{% if isList == true %}
		{% include "blocks/sidebar/search.tpl" %} 
	{% else %}
		<h4>Post Terbaru</h4>
		{% include "blocks/sidebar/preview/post.tpl" %} 
		<a href="/community/post"><i class="icon icon-th-list"></i> Lihat semua post</a>
	{% endif %}
	{{ allowWritePost|displayLinkNewPost|raw }}
{% endblock %}

{% block content %} 
	{% if isList == true %}
		{% if editor == true %}
			{% include "blocks/editor.tpl" %}
		{% else %}
			{% include "blocks/list/post.tpl" %} 
			<a href="#!" data-resource="post" data-query="{{ searchQuery }}" data-page="{{ pagination.nextPage }}" class="btn span8 resource-loader" data-loading-text="<small>Memuat...</small>"><small>Muat lebih banyak</small></a>
		{% endif %}
	{% else %}
		{% include "blocks/detail/post.tpl" %} 
		{% include "blocks/list/comment.tpl" %} 
	{% endif %}
{% endblock %}
