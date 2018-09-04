<?php

/* admin/layout.twig */
class __TwigTemplate_6fcf268768148c0a0e1740c23f4689d15d8a4869483897a5752250ec4054c2f1 extends Twig_Template
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
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
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
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"author\" content=\"Farol360\">

    <title>";
        // line 9
        $this->displayBlock('html_title', $context, $blocks);
        echo "</title>



    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"
    integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">

    <!-- Latest compiled and minified JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\" integrity=\"sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa\" crossorigin=\"anonymous\"></script>

    <!--  Material Dashboard CSS    -->
    <link href=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/css/material-dashboard.css?v=1.2.0\" rel=\"stylesheet\" />

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href=\"";
        // line 26
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/css/demo.css\" rel=\"stylesheet\" />

    <!--     Fonts and icons     -->
    <link href=\"https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css\" rel=\"stylesheet\">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>

    <!-- Admin custom CSS
    <link href=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/css/admin.css\" rel=\"stylesheet\" />
    -->
    ";
        // line 36
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 37
        echo "  </head>

  <body>

    <div class=\"wrapper\">
      <div class=\"sidebar\" data-color=\"purple\" ";
        // line 42
        echo ">
        <div class=\"logo\">
          <a href=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin\" class=\"simple-text\">
            F.A.P. Gestão
          </a>
        </div>
        <div class=\"sidebar-wrapper\">
          <ul class=\"nav\">
            <li ";
        // line 50
        if ((($context["nav_active"] ?? null) == "dashboard")) {
            echo " class=\"active\" ";
        }
        echo ">
              <a href=\"";
        // line 51
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin\">
                <i class=\"material-icons\">
                  dashboard
                </i>
                <p>Painel Inicial</p>
              </a>
            </li>

            <li ";
        // line 59
        if ((($context["nav_active"] ?? null) == "patients")) {
            echo " class=\"active\" ";
        }
        echo ">
              <a href=\"";
        // line 60
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/patients\">
                <i class=\"material-icons\">
                  accessibility
                </i>
                <p>Pacientes</p>
              </a>
            </li>

            <li ";
        // line 68
        if ((($context["nav_active"] ?? null) == "professionals")) {
            echo " class=\"active\" ";
        }
        echo ">
              <a href=\"";
        // line 69
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/professionals\">
                <i class=\"material-icons\">
                  portrait
                </i>
                <p>Profissionais</p>
              </a>
            </li>

            <li ";
        // line 77
        if ((($context["nav_active"] ?? null) == "attendances")) {
            echo " class=\"active\" ";
        }
        echo ">
              <a href=\"";
        // line 78
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/attendances\">
                <i class=\"material-icons\">
                  assignment
                </i>
                <p>Atendimentos</p>
              </a>
            </li>

          </ul>
        </div>
      </div>
      <div class=\"main-panel\">
        <nav class=\"navbar navbar-transparent navbar-absolute\" style=\"padding-bottom:0px;\">
          <div class=\"container-fluid\">
            <div class=\"navbar-header\">
              <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\">
                <span class=\"sr-only\">Toggle navigation</span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
              </button>
              <a class=\"navbar-brand\" href=\"#\"> Painel Administrativo </a>
            </div>
            <div class=\"collapse navbar-collapse\">
              <ul class=\"nav navbar-nav navbar-right\">
                  <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    <p>Estoque</p>
                  </a>
                    <ul class=\"dropdown-menu\">
                      ";
        // line 108
        if (($context["p_admin_suppliers"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/suppliers\">Fornecedores</a></li>";
        }
        // line 109
        echo "                    
                      ";
        // line 110
        if (($context["p_admin_products"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/products\">Produtos</a></li>";
        }
        // line 111
        echo "
                    </ul>
                </li>

                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    <p>Administração</p>
                  </a>
                  <ul class=\"dropdown-menu\">
                    ";
        // line 120
        if (($context["p_admin_diseases"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/diseases\">CID</a></li>";
        }
        // line 121
        echo "                     ";
        if (($context["p_admin_professional_types"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/professional_types\">Categorias de Profissionais</a></li>";
        }
        // line 122
        echo "                     ";
        if (($context["p_admin_products_type"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/products_type\">Categorias de Produtos</a></li>";
        }
        // line 123
        echo "                  </ul>
                </li>

                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    ";
        // line 129
        echo "                    <i class=\"material-icons\">person</i>
                    <p class=\"hidden-lg hidden-md\">Profile</p>
                  </a>
                  <ul class=\"dropdown-menu\">
                    ";
        // line 133
        if (($context["p_admin_user"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/user/all\">Usuários</a></li>";
        }
        // line 134
        echo "                    ";
        if (($context["p_admin_role"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/role\">Cargos</a></li>";
        }
        // line 135
        echo "                    ";
        if (($context["p_admin_permission"] ?? null)) {
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/permission\">Permissões</a></li>
                    <li role=\"separator\" class=\"divider\"></li>
                    ";
        }
        // line 138
        echo "
                    <li><a href=\"";
        // line 139
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/sobre\">Sobre</a></li>
                    <li role=\"separator\" class=\"divider\"></li>
                    <li><a href=\"";
        // line 141
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/signout\">Sair</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <div class=\"content\" style=\"padding-top:10px;\">
          <div class=\"container-fluid\">
            <div class=\"row\">
              <div class=\"col-xs-12\">
                ";
        // line 152
        $this->displayBlock('breadcrumbs', $context, $blocks);
        // line 153
        echo "              </div>
            </div>

            ";
        // line 157
        echo "            <div class=\"row\">
              <div class=\"col-xs-12\">
                ";
        // line 159
        if ((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["flash"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5["success"] ?? null) : null)) {
            // line 160
            echo "                  <div class=\"alert alert-success alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    ";
            // line 162
            echo twig_escape_filter($this->env, (($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["flash"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a["success"] ?? null) : null), "html", null, true);
            echo "
                  </div>
                ";
        }
        // line 165
        echo "                ";
        if ((($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["flash"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57["info"] ?? null) : null)) {
            // line 166
            echo "                  <div class=\"alert alert-info alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    ";
            // line 168
            echo twig_escape_filter($this->env, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["flash"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9["info"] ?? null) : null), "html", null, true);
            echo "
                  </div>
                ";
        }
        // line 171
        echo "                ";
        if ((($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 = ($context["flash"] ?? null)) && is_array($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217) || $__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 instanceof ArrayAccess ? ($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217["warning"] ?? null) : null)) {
            // line 172
            echo "                  <div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    ";
            // line 174
            echo twig_escape_filter($this->env, (($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 = ($context["flash"] ?? null)) && is_array($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105) || $__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 instanceof ArrayAccess ? ($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105["warning"] ?? null) : null), "html", null, true);
            echo "
                  </div>
                ";
        }
        // line 177
        echo "                ";
        if ((($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 = ($context["flash"] ?? null)) && is_array($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779) || $__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 instanceof ArrayAccess ? ($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779["danger"] ?? null) : null)) {
            // line 178
            echo "                  <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    ";
            // line 180
            echo twig_escape_filter($this->env, (($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 = ($context["flash"] ?? null)) && is_array($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1) || $__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 instanceof ArrayAccess ? ($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1["danger"] ?? null) : null), "html", null, true);
            echo "
                  </div>
                ";
        }
        // line 183
        echo "              </div>
            </div>

            ";
        // line 187
        echo "            ";
        $this->displayBlock('content', $context, $blocks);
        // line 188
        echo "
          </div>
        </div>
        <footer class=\"footer\">
          <div class=\"container-fluid\">
            <nav class=\"pull-left\">
              <ul>
                <li>
                  <a href=\"";
        // line 196
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/sobre\">
                      Sobre o Sistema
                  </a>
                </li>
                <li>
                  <a href=\"https://farol360.com.br/\">
                      Farol 360
                  </a>
                </li>

                <li>
                  <a href=\"https://farol360.com.br/portfolio\">
                      Portfolio
                  </a>
                </li>
                <li>
                  <a href=\"https://farol360.com.br/blog\">
                      Blog
                  </a>
                </li>
                <li>
                  <a href=\"https://farol360.com.br/suporte\">
                      Suporte
                  </a>
                </li>
              </ul>
            </nav>
            <p class=\"copyright pull-right\">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>
                Made with Ancora. <small>Create with love by <a href=\"https://farol360.com.br\">Farol360</a>.</small>
            </p>
          </div>
        </footer>
      </div>
    </div>

    <!--   Core JS Files   -->
    <script src=\"";
        // line 236
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/material.min.js\" type=\"text/javascript\"></script>

    <!--  Dynamic Elements plugin -->
    <script src=\"";
        // line 239
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/arrive.min.js\"></script>
    <!--  PerfectScrollbar Library -->
    <script src=\"";
        // line 241
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/perfect-scrollbar.jquery.min.js\"></script>
    <!--  Notifications Plugin    -->
    <script src=\"";
        // line 243
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/bootstrap-notify.js\"></script>
    <!--  Google Maps Plugin
    <script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE\"></script>
    !-->
    <!-- Material Dashboard javascript methods -->
    <script src=\"";
        // line 248
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/material-dashboard.js?v=1.2.0\"></script>


    ";
        // line 251
        $this->displayBlock('javascripts', $context, $blocks);
        // line 252
        echo "  </body>
</html>
";
    }

    // line 9
    public function block_html_title($context, array $blocks = array())
    {
        echo "Ancora - Admin";
    }

    // line 36
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    // line 152
    public function block_breadcrumbs($context, array $blocks = array())
    {
    }

    // line 187
    public function block_content($context, array $blocks = array())
    {
    }

    // line 251
    public function block_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "admin/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  474 => 251,  469 => 187,  464 => 152,  459 => 36,  453 => 9,  447 => 252,  445 => 251,  439 => 248,  431 => 243,  426 => 241,  421 => 239,  415 => 236,  372 => 196,  362 => 188,  359 => 187,  354 => 183,  348 => 180,  344 => 178,  341 => 177,  335 => 174,  331 => 172,  328 => 171,  322 => 168,  318 => 166,  315 => 165,  309 => 162,  305 => 160,  303 => 159,  299 => 157,  294 => 153,  292 => 152,  278 => 141,  273 => 139,  270 => 138,  261 => 135,  254 => 134,  248 => 133,  242 => 129,  235 => 123,  228 => 122,  221 => 121,  215 => 120,  204 => 111,  198 => 110,  195 => 109,  189 => 108,  156 => 78,  150 => 77,  139 => 69,  133 => 68,  122 => 60,  116 => 59,  105 => 51,  99 => 50,  90 => 44,  86 => 42,  79 => 37,  77 => 36,  72 => 34,  61 => 26,  55 => 23,  38 => 9,  28 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html lang=\"pt-br\">
  <head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"author\" content=\"Farol360\">

    <title>{% block html_title %}Ancora - Admin{% endblock %}</title>



    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"
    integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">

    <!-- Latest compiled and minified JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\" integrity=\"sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa\" crossorigin=\"anonymous\"></script>

    <!--  Material Dashboard CSS    -->
    <link href=\"{{ base_url() }}/css/material-dashboard.css?v=1.2.0\" rel=\"stylesheet\" />

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href=\"{{ base_url() }}/css/demo.css\" rel=\"stylesheet\" />

    <!--     Fonts and icons     -->
    <link href=\"https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css\" rel=\"stylesheet\">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>

    <!-- Admin custom CSS
    <link href=\"{{ base_url() }}/css/admin.css\" rel=\"stylesheet\" />
    -->
    {% block stylesheets %}{% endblock %}
  </head>

  <body>

    <div class=\"wrapper\">
      <div class=\"sidebar\" data-color=\"purple\" {# data-image=\"{{ base_url() }}/img/sidebar-1.jpg\" #}>
        <div class=\"logo\">
          <a href=\"{{ base_url() }}/admin\" class=\"simple-text\">
            F.A.P. Gestão
          </a>
        </div>
        <div class=\"sidebar-wrapper\">
          <ul class=\"nav\">
            <li {% if nav_active == \"dashboard\" %} class=\"active\" {% endif %}>
              <a href=\"{{ base_url }}/admin\">
                <i class=\"material-icons\">
                  dashboard
                </i>
                <p>Painel Inicial</p>
              </a>
            </li>

            <li {% if nav_active == \"patients\" %} class=\"active\" {% endif %}>
              <a href=\"{{ base_url }}/admin/patients\">
                <i class=\"material-icons\">
                  accessibility
                </i>
                <p>Pacientes</p>
              </a>
            </li>

            <li {% if nav_active == \"professionals\" %} class=\"active\" {% endif %}>
              <a href=\"{{ base_url }}/admin/professionals\">
                <i class=\"material-icons\">
                  portrait
                </i>
                <p>Profissionais</p>
              </a>
            </li>

            <li {% if nav_active == \"attendances\" %} class=\"active\" {% endif %}>
              <a href=\"{{ base_url }}/admin/attendances\">
                <i class=\"material-icons\">
                  assignment
                </i>
                <p>Atendimentos</p>
              </a>
            </li>

          </ul>
        </div>
      </div>
      <div class=\"main-panel\">
        <nav class=\"navbar navbar-transparent navbar-absolute\" style=\"padding-bottom:0px;\">
          <div class=\"container-fluid\">
            <div class=\"navbar-header\">
              <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\">
                <span class=\"sr-only\">Toggle navigation</span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
              </button>
              <a class=\"navbar-brand\" href=\"#\"> Painel Administrativo </a>
            </div>
            <div class=\"collapse navbar-collapse\">
              <ul class=\"nav navbar-nav navbar-right\">
                  <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    <p>Estoque</p>
                  </a>
                    <ul class=\"dropdown-menu\">
                      {% if p_admin_suppliers %}<li><a href=\"{{ base_url() }}/admin/suppliers\">Fornecedores</a></li>{% endif %}
                    
                      {% if p_admin_products %}<li><a href=\"{{ base_url() }}/admin/products\">Produtos</a></li>{% endif %}

                    </ul>
                </li>

                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    <p>Administração</p>
                  </a>
                  <ul class=\"dropdown-menu\">
                    {% if p_admin_diseases %}<li><a href=\"{{ base_url() }}/admin/diseases\">CID</a></li>{% endif %}
                     {% if p_admin_professional_types %}<li><a href=\"{{ base_url() }}/admin/professional_types\">Categorias de Profissionais</a></li>{% endif %}
                     {% if p_admin_products_type %}<li><a href=\"{{ base_url() }}/admin/products_type\">Categorias de Produtos</a></li>{% endif %}
                  </ul>
                </li>

                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    {#<img src=\"{{ gravatar_url(get_email(), 16) }}\">#}
                    <i class=\"material-icons\">person</i>
                    <p class=\"hidden-lg hidden-md\">Profile</p>
                  </a>
                  <ul class=\"dropdown-menu\">
                    {% if p_admin_user %}<li><a href=\"{{ base_url() }}/admin/user/all\">Usuários</a></li>{% endif %}
                    {% if p_admin_role %}<li><a href=\"{{ base_url() }}/admin/role\">Cargos</a></li>{% endif %}
                    {% if p_admin_permission %}<li><a href=\"{{ base_url() }}/admin/permission\">Permissões</a></li>
                    <li role=\"separator\" class=\"divider\"></li>
                    {% endif %}

                    <li><a href=\"{{ base_url() }}/admin/sobre\">Sobre</a></li>
                    <li role=\"separator\" class=\"divider\"></li>
                    <li><a href=\"{{ base_url() }}/users/signout\">Sair</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <div class=\"content\" style=\"padding-top:10px;\">
          <div class=\"container-fluid\">
            <div class=\"row\">
              <div class=\"col-xs-12\">
                {% block breadcrumbs %}{% endblock %}
              </div>
            </div>

            {# Here code block to flash messagens on view #}
            <div class=\"row\">
              <div class=\"col-xs-12\">
                {% if (flash['success']) %}
                  <div class=\"alert alert-success alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    {{ flash['success'] }}
                  </div>
                {% endif %}
                {% if (flash['info']) %}
                  <div class=\"alert alert-info alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    {{ flash['info'] }}
                  </div>
                {% endif %}
                {% if (flash['warning']) %}
                  <div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    {{ flash['warning'] }}
                  </div>
                {% endif %}
                {% if (flash['danger']) %}
                  <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
                    {{ flash['danger'] }}
                  </div>
                {% endif %}
              </div>
            </div>

            {# Main content of any page#}
            {% block content %}{% endblock %}

          </div>
        </div>
        <footer class=\"footer\">
          <div class=\"container-fluid\">
            <nav class=\"pull-left\">
              <ul>
                <li>
                  <a href=\"{{ base_url() }}/admin/sobre\">
                      Sobre o Sistema
                  </a>
                </li>
                <li>
                  <a href=\"https://farol360.com.br/\">
                      Farol 360
                  </a>
                </li>

                <li>
                  <a href=\"https://farol360.com.br/portfolio\">
                      Portfolio
                  </a>
                </li>
                <li>
                  <a href=\"https://farol360.com.br/blog\">
                      Blog
                  </a>
                </li>
                <li>
                  <a href=\"https://farol360.com.br/suporte\">
                      Suporte
                  </a>
                </li>
              </ul>
            </nav>
            <p class=\"copyright pull-right\">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>
                Made with Ancora. <small>Create with love by <a href=\"https://farol360.com.br\">Farol360</a>.</small>
            </p>
          </div>
        </footer>
      </div>
    </div>

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
", "admin/layout.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\layout.twig");
    }
}
