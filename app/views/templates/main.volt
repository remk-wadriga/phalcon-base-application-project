<!DOCTYPE html>
<html>

<head>

    {% block head %}

    {% endblock %}
    <title>{% block title %}{% endblock %}</title>

</head>

<body>

    <div id="main_wrapper">

        <div id="content">
            {% block content %}{% endblock %}
        </div>

        <div id="footer">
            {% block footer %}&copy; Copyright 2015, All rights reserved.{% endblock %}
        </div>

    </div>

</body>

</html>