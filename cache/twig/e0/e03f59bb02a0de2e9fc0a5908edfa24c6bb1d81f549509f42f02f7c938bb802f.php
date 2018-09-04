<?php

/* admin/professional/index.twig */
class __TwigTemplate_afe1106d0423148c28fb747ecd03d12ff451c6c2e532382b93d74af158403cba extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/professional/index.twig", 1);
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
        $context["nav_active"] = "professionals";
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
          <h3>Profissionais</h3>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">
            <a class=\"btn btn-success\" href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/professionals/add\">
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
                <th>Cargo</th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
              </tr>
            </thead>

            <tbody>
              ";
        // line 45
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["professionals"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["professional"]) {
            // line 46
            echo "                <tr>
                  <td>";
            // line 47
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["professional"], "user_name", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 48
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["professional"], "user_email", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 49
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["professional"], "professional_type_name", array()), "html", null, true);
            echo "</td>
                  <td style=\"width:80px\"><a class=\"btn btn-info\" href=\"";
            // line 50
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/professionals/history/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["professional"], "id", array()), "html", null, true);
            echo "\">
                    Histórico
                  </a></td>
                  <td style=\"width:80px\"><a class=\"btn btn-default\" href=\"";
            // line 53
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/professionals/edit/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["professional"], "id", array()), "html", null, true);
            echo "\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                  </a></td>
                  <td style=\"width:80px\"><a class=\"btn btn-danger\" href=\"";
            // line 56
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/professionals/remove/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["professional"], "id", array()), "html", null, true);
            echo "\">
                    <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
                  </a></td>
                </tr>
              ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 61
            echo "                <div class=\"alert alert-danger\" role=\"alert\">Não existem profissionais cadastrados</div>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['professional'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
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
        return "admin/professional/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 63,  143 => 61,  131 => 56,  123 => 53,  115 => 50,  111 => 49,  107 => 48,  103 => 47,  100 => 46,  95 => 45,  69 => 22,  57 => 12,  54 => 11,  47 => 6,  44 => 5,  37 => 2,  33 => 1,  31 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'professionals' %}

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
          <h3>Profissionais</h3>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">
            <a class=\"btn btn-success\" href=\"{{ base_url() }}/admin/professionals/add\">
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
                <th>Cargo</th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
              </tr>
            </thead>

            <tbody>
              {% for professional in professionals %}
                <tr>
                  <td>{{ professional.user_name }}</td>
                  <td>{{ professional.user_email }}</td>
                  <td>{{ professional.professional_type_name }}</td>
                  <td style=\"width:80px\"><a class=\"btn btn-info\" href=\"{{ base_url() }}/admin/professionals/history/{{ professional.id }}\">
                    Histórico
                  </a></td>
                  <td style=\"width:80px\"><a class=\"btn btn-default\" href=\"{{ base_url() }}/admin/professionals/edit/{{ professional.id }}\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                  </a></td>
                  <td style=\"width:80px\"><a class=\"btn btn-danger\" href=\"{{ base_url() }}/admin/professionals/remove/{{ professional.id }}\">
                    <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
                  </a></td>
                </tr>
              {% else %}
                <div class=\"alert alert-danger\" role=\"alert\">Não existem profissionais cadastrados</div>
              {% endfor %}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{% endblock %}
", "admin/professional/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\professional\\index.twig");
    }
}
