<ul class="nav nav-list sidebar-list">
	{% if articles is not empty %}
	{% for article in articles %}
	<li>
		<div>
			<a href="{{ article.Link }}" class="preview-link" title="{{ article.Title }}">{{ article.previewTitle }}</a>
			<p class="preview-text">
				{{ article.previewText|displayMarkdown|raw|striptags }}
				<span><i class="icon icon-user"></i><a href="{{ article.AuthorLink }}">{{ article.Uid|toUserName }}</a><i class="icon icon-time"></i>{{ article.pubDate }}</span>
				</p>
		</div>
	</li>
	<li class="divider"></li>
	{% endfor %}
	{% endif %}
</ul>