{######################## MASTER ########################}
{% extends "master/single.tpl" %}

{######################## Title ########################}
{% block title %} {{title}} {% endblock %}

{######################## SLIDER ########################}
{% block slider %} {% include "blocks/slider.tpl" %} {% endblock %}

{######################## MODULES ########################}
{% block modules %} {% include "blocks/modules.tpl" %} {% endblock %}

{######################## Content ########################}
{% block content %} {{content}} {% endblock %}