<div>
	<h3 class="subtitle">{{ post.Name }}</h3>
	<div data-provide="markdown-editable-article" data-node="{{ post.Nid }}">
	{{ post|displayPostBody|raw }}
	</div>
	<p class="subtitle">{{ post.pubDate }}</p>	
	<hr/>
</div>