<?php

/* admin/products_type/index.twig */
class __TwigTemplate_b2663878b8120ca9df6724e7dc18d63fdb2c14b5850f1135e8a64992738a8157 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/products_type/index.twig", 1);
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
        $context["nav_active"] = "role";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_html_title($context, array $blocks = array())
    {
        echo "Categorias de Produtos - ";
        $this->displayParentBlock("html_title", $context, $blocks);
    }

    // line 4
    public function block_breadcrumbs($context, array $blocks = array())
    {
        // line 5
        echo "<ol class=\"breadcrumb\">
  <li><a href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin\">Início</a></li>
  <li class=\"active\">Categorias de Produtos</li>
</ol>
";
    }

    // line 10
    public function block_content($context, array $blocks = array())
    {
        // line 11
        echo "
<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-sm-8\">
        <h3>Categorias de Produtos</h3>
      </div>
      <div class=\"col-sm-4\">
        <p class=\"pull-right\">
          <a class=\"btn btn-success\" href=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/products_type/add\">
            <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span>
          </a>
        </p>
      </div>
    </div>
  </div>

  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        ";
        // line 31
        if (($context["products_type"] ?? null)) {
            // line 32
            echo "          <table class=\"table table-striped data-table\">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Descrição</th>
             
              <th class=\"th-button\"></th>
              <th class=\"th-button\"></th>
            </tr>
          </thead>
          <tbody>
            ";
            // line 43
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["products_type"]);
            foreach ($context['_seq'] as $context["_key"] => $context["products_type"]) {
                // line 44
                echo "            <tr>
              <td>";
                // line 45
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["products_type"], "name", array()), "html", null, true);
                echo "</td>
              <td>";
                // line 46
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["products_type"], "description", array()), "html", null, true);
                echo "</td>
             
              <td style=\"width:100px;\"><a class=\"btn btn-default\" href=\"";
                // line 48
                echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
                echo "/admin/products_type/edit/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["products_type"], "id", array()), "html", null, true);
                echo "\">
                <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Editar
              </a></td>
              <td style=\"width:100px;\"><a class=\"btn btn-danger\" href=\"";
                // line 51
                echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
                echo "/admin/products_type/remove/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["products_type"], "id", array()), "html", null, true);
                echo "\">
                <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span> Apagar
              </a></td>
            </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['products_type'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 56
            echo "          </tbody>
          </table>
        ";
        } else {
            // line 59
            echo "          <div class=\"alert alert-danger\" role=\"alert\">Não existem categorias cadastrados</div>
        ";
        }
        // line 61
        echo "      </div>
    </div>
  </div>
</div>



";
    }

    public function getTemplateName()
    {
        return "admin/products_type/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 61,  143 => 59,  138 => 56,  125 => 51,  117 => 48,  112 => 46,  108 => 45,  105 => 44,  101 => 43,  88 => 32,  86 => 31,  72 => 20,  61 => 11,  58 => 10,  50 => 6,  47 => 5,  44 => 4,  37 => 2,  33 => 1,  31 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Categorias de Produtos - {{ parent() }}{% endblock %}
{% set nav_active = 'role' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li class=\"active\">Categorias de Produtos</li>
</ol>
{% endblock %}
{% block content %}

<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-sm-8\">
        <h3>Categorias de Produtos</h3>
      </div>
      <div class=\"col-sm-4\">
        <p class=\"pull-right\">
          <a class=\"btn btn-success\" href=\"{{ base_url() }}/admin/products_type/add\">
            <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span>
          </a>
        </p>
      </div>
    </div>
  </div>

  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        {% if products_type %}
          <table class=\"table table-striped data-table\">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Descrição</th>
             
              <th class=\"th-button\"></th>
              <th class=\"th-button\"></th>
            </tr>
          </thead>
          <tbody>
            {% for products_type in products_type %}
            <tr>
              <td>{{ products_type.name }}</td>
              <td>{{ products_type.description }}</td>
             
              <td style=\"width:100px;\"><a class=\"btn btn-default\" href=\"{{ base_url() }}/admin/products_type/edit/{{ products_type.id }}\">
                <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Editar
              </a></td>
              <td style=\"width:100px;\"><a class=\"btn btn-danger\" href=\"{{ base_url() }}/admin/products_type/remove/{{ products_type.id }}\">
                <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span> Apagar
              </a></td>
            </tr>
            {% endfor %}
          </tbody>
          </table>
        {% else %}
          <div class=\"alert alert-danger\" role=\"alert\">Não existem categorias cadastrados</div>
        {% endif %}
      </div>
    </div>
  </div>
</div>



{% endblock %}
", "admin/products_type/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\products_type\\index.twig");
    }
}
