<ul id="mainTab" class="nav nav-tabs">
	{% for tab in tabs %}
	<li class="{{ tab.liClass }}"><a href="#{{ tab.id }}" data-toggle="tab">{{ tab.link }}</a></li>
	{% endfor %}
</ul>

<div id="mainTabContent" class="tab-content">
	{% for tab in tabs %}
	<div class="tab-pane fade {{ tab.tabClass }}" id="{{ tab.id }}">
		{% if (tab.data is not empty) %}
		{{ tab.data|raw }}
		{% else %}
		<div class="well"><center><small>Tidak ada data</small></center></div>
		{% endif %}
	</div>
	{% endfor %}
</div>