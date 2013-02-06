<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>{{ title }}</title>

  <!-- Mobile viewport optimisation -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
{{ content }}
<ul>
{% for lalala in objects %}
	<li>{{lalala.title}}</li>
{% endfor %}
</ul>
</body>
</html>