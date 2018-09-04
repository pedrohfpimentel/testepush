<?php

/* layout.twig */
class __TwigTemplate_6da88519632b6ed32af3d8712dc3188d9b314bc97ff324e2f44c7796580a3e8c extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'html_title' => array($this, 'block_html_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'content' => array($this, 'block_content'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"pt-br\">
  <head>
    ";
        // line 5
        echo "    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"author\" content=\"Farol360\">

    <!-- Set this when you have you favicon

    <link rel=\"icon\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/favicon.png\" type=\"image/x-icon\">
    !-->

    <title>";
        // line 15
        $this->displayBlock('html_title', $context, $blocks);
        echo "</title>

    <!-- Font -->
    <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500\" rel=\"stylesheet\">

    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"
    integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">

    <!-- Latest compiled and minified JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\" integrity=\"sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa\" crossorigin=\"anonymous\"></script>
    <!-- Stylesheets -->

    <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\"
      rel=\"stylesheet\">

    <!--  Material Dashboard CSS    -->
    <link href=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/css/material-dashboard.css?v=1.2.0\" rel=\"stylesheet\" />

    <!-- Use this when you generate app from sass
    <link href=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/css/app.css\" rel=\"stylesheet\">
    !-->

    ";
        // line 40
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 41
        echo "  </head>

  <body>

    <header>
      <nav class=\"navbar navbar-default\">
        <div class=\"container\">
          <div class=\"navbar-header\">
            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#main-navbar\" aria-expanded=\"false\">
              <span class=\"sr-only\">Toggle navigation</span>
              <span class=\"icon-bar\"></span>
              <span class=\"icon-bar\"></span>
              <span class=\"icon-bar\"></span>
            </button>

            <!-- Here you change your brand !-->
            <a class=\"navbar-brand\" href=\"";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "\" >F.A.P. Gestão </a>
          </div>

          <div class=\"collapse navbar-collapse\" id=\"main-navbar\">
            <ul class=\"nav navbar-nav\">
              ";
        // line 69
        echo "            </ul>
            <ul class=\"nav navbar-nav navbar-right\">";
        // line 71
        if (Farol360\Ancora\User::isAuth()) {
            // line 72
            echo "                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">

                    Olá, ";
            // line 75
            echo twig_escape_filter($this->env, Farol360\Ancora\User::getName(), "html", null, true);
            echo " <i class=\"material-icons\">account_circle</i>
                    </a>

                  <ul class=\"dropdown-menu\">
                    <li><a href=\"";
            // line 79
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/users/profile\">Meu Perfil</a></li>
                    <li role=\"separator\" class=\"divider\"></li>
                    ";
            // line 81
            if (($context["p_admin"] ?? null)) {
                // line 82
                echo "<li><a href=\"";
                echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
                echo "/admin\">Administração</a></li>
                      <li role=\"separator\" class=\"divider\"></li>";
            }
            // line 85
            echo "                      <li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/users/signout\">Sair</a></li>
                  </ul>
                </li>";
        } else {
            // line 89
            echo "                <li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/users/signin\">Login</a></li>
                <li><a href=\"";
            // line 90
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/users/signup\">Registrar</a></li>";
        }
        // line 92
        echo "            </ul>
          </div>
        </div>
      </nav>

    </header>

    ";
        // line 99
        $this->displayBlock('content', $context, $blocks);
        // line 100
        echo "
    <footer class=\"footer\">
      <div class=\"container\">
        <p>
          Ancora. You can change this message on layout.twig footer's tag. <small>Create with love by <a href=\"https://farol360.com.br\">Farol360</a>.</small>
        </p>
      </div>
    </footer>

     <!--   Core JS Files   -->
    <script src=\"";
        // line 110
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/material.min.js\" type=\"text/javascript\"></script>

    <!--  Dynamic Elements plugin -->
    <script src=\"";
        // line 113
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/arrive.min.js\"></script>
    <!--  PerfectScrollbar Library -->
    <script src=\"";
        // line 115
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/perfect-scrollbar.jquery.min.js\"></script>
    <!--  Notifications Plugin    -->
    <script src=\"";
        // line 117
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/bootstrap-notify.js\"></script>
    <!--  Google Maps Plugin
    <script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE\"></script>
    !-->
    <!-- Material Dashboard javascript methods -->
    <script src=\"";
        // line 122
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/material-dashboard.js?v=1.2.0\"></script>


    ";
        // line 125
        $this->displayBlock('javascripts', $context, $blocks);
        // line 126
        echo "  </body>
</html>
";
    }

    // line 15
    public function block_html_title($context, array $blocks = array())
    {
        echo "F.A.P. Gestão";
    }

    // line 40
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    // line 99
    public function block_content($context, array $blocks = array())
    {
    }

    // line 125
    public function block_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  232 => 125,  227 => 99,  222 => 40,  216 => 15,  210 => 126,  208 => 125,  202 => 122,  194 => 117,  189 => 115,  184 => 113,  178 => 110,  166 => 100,  164 => 99,  155 => 92,  151 => 90,  146 => 89,  139 => 85,  133 => 82,  131 => 81,  126 => 79,  119 => 75,  114 => 72,  112 => 71,  109 => 69,  101 => 57,  83 => 41,  81 => 40,  75 => 37,  69 => 34,  47 => 15,  41 => 12,  32 => 5,  27 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html lang=\"pt-br\">
  <head>
    {# charset #}
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"author\" content=\"Farol360\">

    <!-- Set this when you have you favicon

    <link rel=\"icon\" href=\"{{ base_url() }}/favicon.png\" type=\"image/x-icon\">
    !-->

    <title>{% block html_title %}F.A.P. Gestão{% endblock %}</title>

    <!-- Font -->
    <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500\" rel=\"stylesheet\">

    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"
    integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">

    <!-- Latest compiled and minified JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\" integrity=\"sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa\" crossorigin=\"anonymous\"></script>
    <!-- Stylesheets -->

    <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\"
      rel=\"stylesheet\">

    <!--  Material Dashboard CSS    -->
    <link href=\"{{ base_url() }}/css/material-dashboard.css?v=1.2.0\" rel=\"stylesheet\" />

    <!-- Use this when you generate app from sass
    <link href=\"{{ base_url() }}/css/app.css\" rel=\"stylesheet\">
    !-->

    {% block stylesheets %}{% endblock %}
  </head>

  <body>

    <header>
      <nav class=\"navbar navbar-default\">
        <div class=\"container\">
          <div class=\"navbar-header\">
            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#main-navbar\" aria-expanded=\"false\">
              <span class=\"sr-only\">Toggle navigation</span>
              <span class=\"icon-bar\"></span>
              <span class=\"icon-bar\"></span>
              <span class=\"icon-bar\"></span>
            </button>

            <!-- Here you change your brand !-->
            <a class=\"navbar-brand\" href=\"{{ base_url() }}\" >F.A.P. Gestão </a>
          </div>

          <div class=\"collapse navbar-collapse\" id=\"main-navbar\">
            <ul class=\"nav navbar-nav\">
              {#

               // Example of menu usage with twig. use \"nav_active\" in page twig to set active class in menu.

              <li {% if nav_active == 'firstUseCase' %} class=\"active\" {% else %} {% endif %}><a href=\"{{ base_url() }}/eventos\">First Use Case</a></li>

              #}
            </ul>
            <ul class=\"nav navbar-nav navbar-right\">
              {%- if is_auth() %}
                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">

                    Olá, {{ get_name() }} <i class=\"material-icons\">account_circle</i>
                    </a>

                  <ul class=\"dropdown-menu\">
                    <li><a href=\"{{ base_url() }}/users/profile\">Meu Perfil</a></li>
                    <li role=\"separator\" class=\"divider\"></li>
                    {% if p_admin -%}
                      <li><a href=\"{{ base_url() }}/admin\">Administração</a></li>
                      <li role=\"separator\" class=\"divider\"></li>
                    {%- endif %}
                      <li><a href=\"{{ base_url() }}/users/signout\">Sair</a></li>
                  </ul>
                </li>
              {%- else %}
                <li><a href=\"{{ base_url() }}/users/signin\">Login</a></li>
                <li><a href=\"{{ base_url() }}/users/signup\">Registrar</a></li>
              {%- endif %}
            </ul>
          </div>
        </div>
      </nav>

    </header>

    {% block content %}{% endblock %}

    <footer class=\"footer\">
      <div class=\"container\">
        <p>
          Ancora. You can change this message on layout.twig footer's tag. <small>Create with love by <a href=\"https://farol360.com.br\">Farol360</a>.</small>
        </p>
      </div>
    </footer>

     <!--   Core JS Files   -->
    <script src=\"{{ base_url() }}/js/material.min.js\" type=\"text/javascript\"></script>

    <!--  Dynamic Elements plugin -->
    <script src=\"{{ base_url() }}/js/arrive.min.js\"></script>
    <!--  PerfectScrollbar Library -->
    <script src=\"{{ base_url() }}/js/perfect-scrollbar.jquery.min.js\"></script>
    <!--  Notifications Plugin    -->
    <script src=\"{{ base_url() }}/js/bootstrap-notify.js\"></script>
    <!--  Google Maps Plugin
    <script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE\"></script>
    !-->
    <!-- Material Dashboard javascript methods -->
    <script src=\"{{ base_url() }}/js/material-dashboard.js?v=1.2.0\"></script>


    {% block javascripts %}{% endblock %}
  </body>
</html>
", "layout.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\layout.twig");
    }
}
