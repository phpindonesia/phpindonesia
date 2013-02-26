{% if alertMessage is not empty %}
$('.notifications').notify({

	{% if alertType is not empty %}
	type: '{{ alertType }}',
	{% else %}
	type: 'bangTidy',
	{% endif %}

	message: {html: "{{ alertMessage|raw }}" },

	{% if alertTimeout is not empty %}
	fadeOut: { enabled: true, delay: {{ alertTimeout }} },
	{% endif %}

}).show();
{% endif %}

{% if parseCode == true %}
var codeParseable = document.getElementsByClassName('codeParseable');

for (i=0;i<codeParseable.length;i++) {
	var codeParseableElem = codeParseable[i];
	var editor = CodeMirror.fromTextArea(codeParseableElem, {
		lineNumbers: false
	});
	editor.setOption('theme', 'monokai');
}
{% endif %}