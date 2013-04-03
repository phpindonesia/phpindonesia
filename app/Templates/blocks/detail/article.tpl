<div>
	<h3 data-provide="input-editable-article" data-node="{{ article.Nid }}"><a href="{{ article.Link }}" title="{{ article.Title }}">{{ article.Title }}</a></h3>
	<p class="subtitle">oleh <a href="{{ article.AuthorLink }}">{{ article.Uid|toUserFullName }}</a> pada {{ article.pubDate }}</p>	
	<hr/>
	<div data-provide="markdown-editable-article" data-node="{{ article.Nid }}">
	{{ article|displayArticleBody|raw }}
	</div>
	<hr/>
</div>