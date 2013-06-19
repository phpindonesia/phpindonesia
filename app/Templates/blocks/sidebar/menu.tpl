<div id="sidebar">
	<ul class="nav nav-list">
		{% for menu in menus %}
		<li class="{{ menu.liClass }}">
			{% if menu.link is not empty %}
			<a href="{{ menu.link }}"><i class="icon {{ menu.icon }}"></i>{{ menu.text }}</a>
			{% elseif menu.text is not empty %}
			{{ menu.text }}
			{% endif %}
		</li>
		{% endfor %}
	</ul>
</div>