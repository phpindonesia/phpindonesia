<table class="table table-hover">
	<thead>
	<h4>Jawaban</h4>
	</thead>
	<tbody>
	{% if comments is not empty %}
	{% include "blocks/list/tr_comment.tpl" %}
	{% else %}
	<tr>
		<div class="alert alert-error">Tidak ditemukan</div>
	</tr>
	{% endif %}
	</tbody>
</table>