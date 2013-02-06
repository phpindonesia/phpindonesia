<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>{{ title }}</title>

        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {% stylesheets 'bootstrap/less/bootstrap.less' filter='less' output='/assets/css/main.css' %}
        <link href="{{ asset_url }}" type="text/css" rel="stylesheet" />
        {% endstylesheets %}
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    </head>
    <body>
        {{ content }}
        {% javascripts 
'bootstrap/js/bootstrap-alert.js'
'bootstrap/js/bootstrap-modal.js' 
'bootstrap/js/bootstrap-dropdown.js' 
'bootstrap/js/bootstrap-scrollspy.js' 
'bootstrap/js/bootstrap-tab.js' 
'bootstrap/js/bootstrap-tooltip.js' 
'bootstrap/js/bootstrap-popover.js' 
'bootstrap/js/bootstrap-button.js' 
'bootstrap/js/bootstrap-collapse.js' 
'bootstrap/js/bootstrap-carousel.js' 
'bootstrap/js/bootstrap-typeahead.js' 
'bootstrap/js/bootstrap-affix.js' output="/assets/js/bootstrap.js" %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}
    </body>
</html>