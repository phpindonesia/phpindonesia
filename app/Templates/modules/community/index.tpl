{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} 
	{% include "blocks/sidebar/search.tpl" %} 
	{{ allowWriteArticle|displayLinkNewArticle|raw }}
	<hr/>
	<h4>Tulisan Terbaru</h4>
	{% include "blocks/sidebar/preview/article.tpl" %} 
	<a href="/community/article"><i class="icon icon-th-list"></i> Lihat semua tulisan</a>
{% endblock %}

{% block content %} 
	{% include "blocks/list/post.tpl" %}
	<a href="/community/post"><i class="icon icon-th-list"></i> Semua Post</a>
	<hr/>
	{% include "blocks/list/user.tpl" %} 
	<a href="/user"><i class="icon icon-th-list"></i> Semua Pengguna</a>
{% endblock %}