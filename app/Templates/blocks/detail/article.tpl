<div>
	<h3><a href="{{ article.Link }}" title="{{ article.Title }}">{{ article.Title }}</a></h3>
	<p class="subtitle">oleh <a href="{{ article.AuthorLink }}">{{ article.Uid|toUserFullName }}</a> pada {{ article.pubDate }}</p>	
	<hr/>
	<div>
	{{ article|displayArticleBody|raw }}
	</div>
	<hr/>
</div>