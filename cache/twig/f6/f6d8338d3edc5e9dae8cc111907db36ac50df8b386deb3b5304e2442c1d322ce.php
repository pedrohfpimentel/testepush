<?php

/* admin/attendance/index.twig */
class __TwigTemplate_70628903217af1cb000f2a68c33b2a6cf0268e9af67709921f021cd1eca3dc24 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/attendance/index.twig", 1);
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
        $context["nav_active"] = "attendances";
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
  <div class=\"\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-3\">
        <a class=\"btn btn-success\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/attendances/add\">
          <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span> Novo Atendimento
        </a>
      </div>
      <div class=\"col-sm-9\">
        <div class=\"card\">
          <div class=\"card-header\" data-background-color=\"purple\">
            Busca
          </div>
          <div class=\"card-content\">
            <div class=\"col-sm-2\">
              <div class=\"form-group\">
                <label for=\"id\">ID</label>
                <input id=\"id\" type=\"text\" name=\"id\" class=\"form-control\">
              </div>
            </div>
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"patient_name\">Nome Paciente</label>
                <input id=\"patient_name\" type=\"text\" name=\"patient_name\" class=\"form-control\">
              </div>
            </div>
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"professional_name\">Nome Profissional</label>
                <input id=\"professional_name\" type=\"text\" name=\"professional_name\" class=\"form-control\">
              </div>
            </div>
            <div class=\"col-sm-2\">
              <div class=\"form-group\">

                <button class=\"btn btn-info\">Buscar</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class=\"card\">
    <div class=\"card-header\" data-background-color=\"blue\">
      <div class=\"row\">
        <div class=\"col-sm-8\">
          <h4>Últimos Atendimentos</h4>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">

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
                <th>ID</th>
                <th>Data</th>
                <th>Profissional</th>
                <th>Paciente</th>
                <th class=\"th-button\"></th>

              </tr>
            </thead>

            <tbody>
              ";
        // line 87
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["attendances"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["attendance"]) {
            // line 88
            echo "                <tr>
                  <td>";
            // line 89
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["attendance"], "id", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 90
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["attendance"], "date", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 91
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["attendance"], "professional_name", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 92
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["attendance"], "patient_name", array()), "html", null, true);
            echo "</td>
                  <td style=\"width:80px\"><a class=\"btn btn-info\" href=\"";
            // line 93
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/attendances/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["attendance"], "id", array()), "html", null, true);
            echo "\">
                    Detalhes
                  </a></td>

                </tr>
              ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 99
            echo "                <div class=\"alert alert-danger\" role=\"alert\">Não existem Atendimentos cadastrados</div>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attendance'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 101
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
        return "admin/attendance/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  181 => 101,  174 => 99,  161 => 93,  157 => 92,  153 => 91,  149 => 90,  145 => 89,  142 => 88,  137 => 87,  64 => 17,  57 => 12,  54 => 11,  47 => 6,  44 => 5,  37 => 2,  33 => 1,  31 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'attendances' %}

{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li class=\"active\">Início</li>
</ol>
{% endblock %}

{% block content %}

<div class=\"main\">
  <div class=\"\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-3\">
        <a class=\"btn btn-success\" href=\"{{ base_url() }}/admin/attendances/add\">
          <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span> Novo Atendimento
        </a>
      </div>
      <div class=\"col-sm-9\">
        <div class=\"card\">
          <div class=\"card-header\" data-background-color=\"purple\">
            Busca
          </div>
          <div class=\"card-content\">
            <div class=\"col-sm-2\">
              <div class=\"form-group\">
                <label for=\"id\">ID</label>
                <input id=\"id\" type=\"text\" name=\"id\" class=\"form-control\">
              </div>
            </div>
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"patient_name\">Nome Paciente</label>
                <input id=\"patient_name\" type=\"text\" name=\"patient_name\" class=\"form-control\">
              </div>
            </div>
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"professional_name\">Nome Profissional</label>
                <input id=\"professional_name\" type=\"text\" name=\"professional_name\" class=\"form-control\">
              </div>
            </div>
            <div class=\"col-sm-2\">
              <div class=\"form-group\">

                <button class=\"btn btn-info\">Buscar</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class=\"card\">
    <div class=\"card-header\" data-background-color=\"blue\">
      <div class=\"row\">
        <div class=\"col-sm-8\">
          <h4>Últimos Atendimentos</h4>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">

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
                <th>ID</th>
                <th>Data</th>
                <th>Profissional</th>
                <th>Paciente</th>
                <th class=\"th-button\"></th>

              </tr>
            </thead>

            <tbody>
              {% for attendance in attendances %}
                <tr>
                  <td>{{ attendance.id }}</td>
                  <td>{{ attendance.date }}</td>
                  <td>{{ attendance.professional_name }}</td>
                  <td>{{ attendance.patient_name }}</td>
                  <td style=\"width:80px\"><a class=\"btn btn-info\" href=\"{{ base_url() }}/admin/attendances/{{ attendance.id }}\">
                    Detalhes
                  </a></td>

                </tr>
              {% else %}
                <div class=\"alert alert-danger\" role=\"alert\">Não existem Atendimentos cadastrados</div>
              {% endfor %}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{% endblock %}
", "admin/attendance/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\attendance\\index.twig");
    }
}
