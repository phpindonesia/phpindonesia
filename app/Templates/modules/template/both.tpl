{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Sidebar - Left #######################}
{% block sidebar_left %}
<h4>Jejak Pendapat</h4>
<ul class="unstyled">
	<li>450 setuju <span class="pull-right strong">45%</span>
		<div class="progress progress-striped active progress-success">
			<div class="bar" style="width: 45%;"></div>
		</div>
	</li>
	<li>300 tidak setuju <span class="pull-right strong">30%</span>
		<div class="progress progress-striped active progress-danger">
			<div class="bar" style="width: 30%;"></div>
		</div>
	</li>
</ul>
<hr>

<h4>Menu</h4>
<div class="sidebar-nav">
	<div class="well" style="padding: 8px 0;">
		<ul class="nav nav-list"> 
			<li class="nav-header">Contoh</li>        
			<li><a href="#">Menu A</a></li>
			<li><a href="#">Menu B</a></li>
			<li><a href="#">Menu C</a></li>
			<li class="active"><a href="#">Menu D</a></li>
			<li class="divider"></li>
			<li><a href="#">Menu E</a></li>
			<li><a href="#">Menu F</a></li>
		</ul>
	</div>
</div>
{% endblock %}

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