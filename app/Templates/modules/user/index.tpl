{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} {% include "blocks/sidebar/search.tpl" %} {% endblock %}
{% block content %} {% include "blocks/list/user.tpl" %} {% endblock %}