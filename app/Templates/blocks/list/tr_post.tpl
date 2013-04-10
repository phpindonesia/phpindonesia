{% for post in posts %}

<tr>
	{% if withoutAvatar == false %}
	<td class="span1"><img src="{{ post.Uid|toUserAvatar }}?d=retro" class="img-polaroid" /></td>
	{% endif %}
	<td><a href="/community/post/{{ post.Nid }}" class="preview-link"><strong>{{ post.previewText|striptags }}</strong></a>
		<p class="subtitle">oleh <strong>{{ post.Name }}</strong> pada {{ post.pubDate }}</p>
	</td>

	<td class="span1"><button class="btn btn-mini disabled">Laporkan</button></td>
</tr>
{% endfor %}