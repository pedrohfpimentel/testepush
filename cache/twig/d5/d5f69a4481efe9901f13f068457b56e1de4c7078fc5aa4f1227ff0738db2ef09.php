<?php

/* admin/patient/index.twig */
class __TwigTemplate_90f1e2a5a82ba24a014f1cba21859e644ace9deb98fae78d9c16c7c7a323bcdf extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/patient/index.twig", 1);
        $this->blocks = array(
            'html_title' => array($this, 'block_html_title'),
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["nav_active"] = "patients";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_html_title($context, array $blocks = array())
    {
        echo "Administração - ";
        $this->displayParentBlock("html_title", $context, $blocks);
    }

    // line 5
    public function block_breadcrumbs($context, array $blocks = array())
    {
        // line 6
        echo "<ol class=\"breadcrumb\">
  <li class=\"active\">Início</li>
</ol>
";
    }

    // line 11
    public function block_content($context, array $blocks = array())
    {
        // line 12
        echo "
<div class=\"main\">
  <div class=\"card\">
    <div class=\"card-header\" data-background-color=\"blue\">
      <div class=\"row\">
        <div class=\"col-sm-8\">
          <h3>Pacientes</h3>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">
            <a class=\"btn btn-success\" href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/patients/add\">
              <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span>
            </a>
          </p>
        </div>
      </div>

    </div>
    <div class=\"card-content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <table class=\"table table-striped data-table\">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>CID Versão</th>
                <th>CID COD</th>
                <th>Cidade</th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
              </tr>
            </thead>

            <tbody>
              ";
        // line 49
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["patients"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["patient"]) {
            // line 50
            echo "                <tr>
                  <td>";
            // line 51
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "name", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 52
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "email", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 53
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "disease_cid_version", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 54
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "disease_cid_code", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 55
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "end_cidade", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 56
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "status", array()), "html", null, true);
            echo "</td>
                  <td style=\"width:100px\"><a class=\"btn btn-info\" href=\"";
            // line 57
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/patients/history/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", array()), "html", null, true);
            echo "\">
                    Histórico
                  </a></td>
                  <td style=\"width:100px\"><a class=\"btn btn-default\" href=\"";
            // line 60
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/patients/edit/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", array()), "html", null, true);
            echo "\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                  </a></td>
                  <td style=\"width:100px\"><a class=\"btn btn-danger\" href=\"";
            // line 63
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/patients/remove/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", array()), "html", null, true);
            echo "\">
                    <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>  </a></td>
                </tr>
              ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 67
            echo "                <div class=\"alert alert-danger\" role=\"alert\">Não existem pacientes cadastrados</div>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['patient'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 69
        echo "            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

";
    }

    public function getTemplateName()
    {
        return "admin/patient/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  165 => 69,  158 => 67,  147 => 63,  139 => 60,  131 => 57,  127 => 56,  123 => 55,  119 => 54,  115 => 53,  111 => 52,  107 => 51,  104 => 50,  99 => 49,  69 => 22,  57 => 12,  54 => 11,  47 => 6,  44 => 5,  37 => 2,  33 => 1,  31 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'patients' %}

{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li class=\"active\">Início</li>
</ol>
{% endblock %}

{% block content %}

<div class=\"main\">
  <div class=\"card\">
    <div class=\"card-header\" data-background-color=\"blue\">
      <div class=\"row\">
        <div class=\"col-sm-8\">
          <h3>Pacientes</h3>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">
            <a class=\"btn btn-success\" href=\"{{ base_url() }}/admin/patients/add\">
              <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span>
            </a>
          </p>
        </div>
      </div>

    </div>
    <div class=\"card-content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <table class=\"table table-striped data-table\">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>CID Versão</th>
                <th>CID COD</th>
                <th>Cidade</th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
              </tr>
            </thead>

            <tbody>
              {% for patient in patients %}
                <tr>
                  <td>{{ patient.name }}</td>
                  <td>{{ patient.email }}</td>
                  <td>{{ patient.disease_cid_version }}</td>
                  <td>{{ patient.disease_cid_code }}</td>
                  <td>{{ patient.end_cidade }}</td>
                  <td>{{ patient.status }}</td>
                  <td style=\"width:100px\"><a class=\"btn btn-info\" href=\"{{ base_url() }}/admin/patients/history/{{ patient.id }}\">
                    Histórico
                  </a></td>
                  <td style=\"width:100px\"><a class=\"btn btn-default\" href=\"{{ base_url() }}/admin/patients/edit/{{ patient.id }}\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                  </a></td>
                  <td style=\"width:100px\"><a class=\"btn btn-danger\" href=\"{{ base_url() }}/admin/patients/remove/{{ patient.id }}\">
                    <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>  </a></td>
                </tr>
              {% else %}
                <div class=\"alert alert-danger\" role=\"alert\">Não existem pacientes cadastrados</div>
              {% endfor %}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{% endblock %}
", "admin/patient/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\patient\\index.twig");
    }
}
