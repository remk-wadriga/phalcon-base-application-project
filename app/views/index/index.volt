
{% extends "templates/main.volt" %}

{% block title %}Hello world page{% endblock %}

{% block content %}

    <h1>Hello {{name}}!</h1>
    {{link_to('signin', 'Login')}}
    <br />
    {{link_to('signup', 'Sighup')}}

{% endblock %}