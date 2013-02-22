<div id="sidebar">
	<ul class="nav nav-list">
		<li class="nav-header">Pencarian</li>
		<li><form method="POST" action="{{ currentUrl }}"><input name="query" type="text" placeholder="Kata kunci atau tag" class="input-medium" /></form></li>

        {% if (filters is not empty) %}
		<li class="divider"></li>
		<li class="nav-header">Filter</li>
		{% for filter in filters %}
	        <li class="{{ filter.class }}"><a href="{{ filter.href }}" id="{{ filter.id }}">{{ filter.text }}</a></li>
	    {% endfor %}
        {% endif %}
	</ul>
</div>