<div>
	<div data-provide="markdown-editable-article" data-node="{{ post.Nid }}">
	{{ post|displayPostBody|raw }}
	</div>
	<p class="subtitle"><strong>{{ post.Name }}</strong> pada {{ post.pubDate }}</p>	
</div>
<br/>