<table class="table table-hover">
	<thead>
	<h4>{{ listTitle }} ({{ pagination.totalText }})
	{% if pagination is not empty and pagination.data is not empty %}
	<span class="pull-right"><small>Halaman {{ pagination.currentPage }} dari {{ pagination.totalPage }}</small></span>
	{% endif %}
	</h4>
	</thead>
	<tbody>
	{% if articles is not empty %}
	{% for article in articles %}

	<tr>
		{% if withoutAvatar == false %}
		<td class="span1"><img src="{{ article.Uid|toUserAvatar }}?d=retro" class="img-polaroid" /></td>
		{% endif %}
		<td><a href="/community/article/{{ article.Nid }}" class="preview-link"><strong>{{ article.Title }}</strong></a>
			<p class="subtitle">oleh <a href="{{ article.AuthorLink }}">{{ article.Uid|toUserFullName }}</a> pada {{ article.pubDate }}</p>
			<p class="preview-text">{{ article.previewMediumText|displayMarkdown|raw|striptags }}</p>
		</td>

		<td class="span1"><button class="btn btn-mini disabled">Laporkan</button></td>
	</tr>
	{% endfor %}
	{% else %}
	<tr>
		<div class="alert alert-error">Tidak ditemukan</div>
	</tr>
	{% endif %}
	</tbody>
</table>

{% if pagination is not empty and pagination.data is not empty %}
<div class="pagination">
  <ul>
    <li><a href="{{ currentQueryUrl|raw }}page={{ pagination.previousPage }}"><i class="icon icon-backward"></i></a></li>
    {% for paging in pagination.pages %}
    <li class="{{ paging.class }}"><a href="{{ currentQueryUrl|raw }}page={{ paging.number }}">{{ paging.number }}</a></li>
    {% endfor %}
    <li><a href="{{ currentQueryUrl|raw }}page={{ pagination.nextPage }}"><i class="icon icon-forward"></i></a></li>
  </ul>
</div>
{% endif %}