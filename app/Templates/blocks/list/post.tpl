<table class="table table-hover">
	<thead>
	<h4>Forum</h4>
	</thead>
	<tbody>
	{% if posts is not empty %}
	{% include "blocks/list/tr_post.tpl" %}
	{% else %}
	<tr>
		<div class="alert alert-error">Tidak ditemukan</div>
	</tr>
	{% endif %}
	</tbody>
</table>