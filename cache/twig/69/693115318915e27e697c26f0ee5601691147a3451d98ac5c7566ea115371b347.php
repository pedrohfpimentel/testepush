<?php

/* admin/disease/index.twig */
class __TwigTemplate_3676bf1282133c4f988738f7423ce9dc98738e9fa45d93e5fd250baf28adddac extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/disease/index.twig", 1);
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
        $context["nav_active"] = "diseases";
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
          <h3>Doenças</h3>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">
            <a class=\"btn btn-success\" href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/diseases/add\">
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
                <th>Descrição</th>
                <th>CID Versão</th>
                <th>CID COD</th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
              </tr>
            </thead>

            <tbody>
              ";
        // line 46
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["diseases"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["disease"]) {
            // line 47
            echo "                <tr>
                  <td>";
            // line 48
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "name", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 49
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "description", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 50
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "cid_version", array()), "html", null, true);
            echo "</td>
                  <td>";
            // line 51
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "cid_code", array()), "html", null, true);
            echo "</td>
                  <td style=\"width:100px\"><a class=\"btn btn-default\" href=\"";
            // line 52
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/diseases/edit/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "id", array()), "html", null, true);
            echo "\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Editar
                  </a></td>
                  <td style=\"width:100px\"><a class=\"btn btn-danger\" href=\"";
            // line 55
            echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            echo "/admin/diseases/remove/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "id", array()), "html", null, true);
            echo "\">
                    <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span> Apagar
                  </a></td>
                </tr>
              ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 60
            echo "                <div class=\"alert alert-danger\" role=\"alert\">Não existem doenças cadastrados</div>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['disease'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 62
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
        return "admin/disease/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 62,  140 => 60,  128 => 55,  120 => 52,  116 => 51,  112 => 50,  108 => 49,  104 => 48,  101 => 47,  96 => 46,  69 => 22,  57 => 12,  54 => 11,  47 => 6,  44 => 5,  37 => 2,  33 => 1,  31 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'diseases' %}

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
          <h3>Doenças</h3>
        </div>
        <div class=\"col-sm-4\">
          <p class=\"pull-right\">
            <a class=\"btn btn-success\" href=\"{{ base_url() }}/admin/diseases/add\">
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
                <th>Descrição</th>
                <th>CID Versão</th>
                <th>CID COD</th>
                <th class=\"th-button\"></th>
                <th class=\"th-button\"></th>
              </tr>
            </thead>

            <tbody>
              {% for disease in diseases %}
                <tr>
                  <td>{{ disease.name }}</td>
                  <td>{{ disease.description }}</td>
                  <td>{{ disease.cid_version }}</td>
                  <td>{{ disease.cid_code }}</td>
                  <td style=\"width:100px\"><a class=\"btn btn-default\" href=\"{{ base_url() }}/admin/diseases/edit/{{ disease.id }}\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Editar
                  </a></td>
                  <td style=\"width:100px\"><a class=\"btn btn-danger\" href=\"{{ base_url() }}/admin/diseases/remove/{{ disease.id }}\">
                    <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span> Apagar
                  </a></td>
                </tr>
              {% else %}
                <div class=\"alert alert-danger\" role=\"alert\">Não existem doenças cadastrados</div>
              {% endfor %}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{% endblock %}
", "admin/disease/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\disease\\index.twig");
    }
}
