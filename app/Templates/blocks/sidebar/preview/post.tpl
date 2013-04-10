<ul class="nav nav-list sidebar-list">
	{% if posts is not empty %}
	{% for post in posts %}
	<li>
		<div>
			<a href="{{ post.Link }}" class="preview-link" title="{{ post.Title }}"><small>{{ post.previewText }}</small></a>
			<p class="preview-text">
				<span><i class="icon icon-user"></i>{{ post.Name }} <i class="icon icon-time"></i>{{ post.pubDate }}</span>
				</p>
		</div>
	</li>
	<li class="divider"></li>
	{% endfor %}
	{% endif %}
</ul>