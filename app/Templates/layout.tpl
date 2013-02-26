<!DOCTYPE html>
<html>
<head>
    {% block head %}
        <meta charset="utf-8">

        {% set _title = block('title') %}

        {% if (_title is not empty) %}
            <title>{% block title %}{% endblock %} - PHP Indonesia</title>
        {% else %}
            <title>{{ title }} - PHP Indonesia</title>
        {% endif %}

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="/asset/css/main.css" rel="stylesheet">

        {% if parseCode == true %}
        <link href="/asset/css/code.css" rel="stylesheet">
        <script src="/asset/js/code.js"></script>
        {% endif %}
        

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/asset/img/favicon/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/asset/img/favicon/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/asset/img/favicon/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/asset/img/favicon/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="/asset/img/favicon/favicon.png">
    {% endblock %}
</head>

<body>
    <div class="notifications transparent-75 bottom-right"></div>
    <div id="wrap">

        {######################## Header ########################}
        {% include "blocks/header.tpl" %}

        {######################## Slider ########################}
        {% block slider %}{% endblock %}

        <div id="body">
            <div class="container">
                <div class="row">

                    {% set _content         = block('content') %}
                    {% set _sidebar_left    = block('sidebar_left') %}
                    {% set _sidebar_right   = block('sidebar_right') %}

                    {% if (_content is not empty) %}

                        {% if (_sidebar_left is not empty) %}
                            <div class="span3">
                                {% block sidebar_left %}{% endblock %}
                            </div>
                        {% endif %}

                        {% if (_sidebar_left is not empty) or (_sidebar_right is not empty) %}
                            {% if (_sidebar_left is not empty) and (_sidebar_right is not empty) %}
                                <div class="span6">
                            {% else %}
                                <div class="span9">
                            {% endif %}
                        {% else %}
                            <div class="span12"> 
                        {% endif %}

                            {% block content %}{% endblock %}
                        </div>

                        {% if (_sidebar_right is not empty) %}
                            <div class="span3">
                                {% block sidebar_right %}{% endblock %}
                            </div>
                        {% endif %}

                    {% else %}
                    <div class="span12">
                    {{ content }}
                    </div>
                    {% endif %}

                </div>

                {######################## Modules ########################}
                {% block modules %}{% endblock %}
            </div>
        </div>
        
        <div id="push"></div>
    </div>

    {######################## Footer ########################}
    {% include "blocks/footer.tpl" %}

    <script src="/asset/js/app.js"></script>
    <script>
    {% include "inline.tpl" %}
    </script>
</body>

</html>