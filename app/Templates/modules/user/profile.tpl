{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Content ########################}
{% block sidebar_left %} {% include "blocks/sidebar/profile.tpl" %} {% endblock %}
{% block content %} {% include "blocks/tab.tpl" %} {% endblock %}