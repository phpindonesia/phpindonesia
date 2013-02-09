{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Sidebar - Left #######################}
{% block sidebar_left %}{% endblock %}

{######################## Sidebar - Right #######################}
{% block sidebar_right %}
<div class="row">
	<div class="span1">
		<a href="http://critterapp.pagodabox.com/others/admin" class="thumbnail">
			<img src="https://m.ak.fbcdn.net/profile.ak/hprofile-ak-ash4/371487_1109853220_1153196934_q.jpg" alt="">
		</a>
	</div>
	<div class="span2">
		<p><strong>Anne Regina Nancy</strong></p>
		<p class="text-error"><i class="icon-heart"></i> <small><i>DIA tetaplah DIA..</i></small></p>
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="#" class="btn btn"><i class="icon-white icon-thumbs-up"></i></a>
				<a href="#" class="btn btn-danger"><i class="icon-white icon-heart"></i></a>
				<a href="#" class="btn btn"><i class="icon-white icon-share-alt"></i></a>
			</div>
		</div>
	</div>
</div>
{% endblock %}

{######################## Content ########################}
{% block content %} {% include "modules/template/content.tpl" %} {% endblock %}