{% for comment in comments %}

<tr>
	{% if withoutAvatar == false %}
	<td class="span1"><img src="{{ comment.Uid|toUserAvatar }}?d=retro" class="img-polaroid" /></td>
	{% endif %}
	<td>{{ comment.Thread|striptags }}
		<p class="subtitle">oleh <strong>{{ comment.Name }}</strong> pada {{ comment.Created|toDate }}</p>
	</td>

	<td class="span1"><button class="btn btn-mini disabled">Laporkan</button></td>
</tr>
{% endfor %}