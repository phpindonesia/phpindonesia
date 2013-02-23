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