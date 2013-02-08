{######################## MASTER ########################}
{% extends "layout.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## Sidebar - Left #######################}
{% block sidebar_left %}{% endblock %}

{######################## Sidebar - Right #######################}
{% block sidebar_right %}{% endblock %}

{######################## Content ########################}
{% block content %} {% include "modules/template/content.tpl" %} {% endblock %}