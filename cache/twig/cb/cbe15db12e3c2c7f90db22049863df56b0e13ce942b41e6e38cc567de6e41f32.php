<?php

/* admin/products_type/edit.twig */
class __TwigTemplate_5f30aaf517d3a2d33b20f53fd00e1c943608d9ee8efea2768c6b071e5442bab0 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/products_type/edit.twig", 1);
        $this->blocks = array(
            'html_title' => array($this, 'block_html_title'),
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
            'content' => array($this, 'block_content'),
            'javascripts' => array($this, 'block_javascripts'),
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
  <li><a href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/products_type\">Cargos</a></li>
  <li class=\"active\">";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products_type"] ?? null), "name", array()), "html", null, true);
        echo "</li>

</ol>
";
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "
<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Editar Cargo</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/products_type/update\" method=\"POST\">
        <input type=\"hidden\" name=\"id\" value=\"";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products_type"] ?? null), "id", array()), "html", null, true);
        echo "\">
        <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"\" title=\"Nome da Categoria\" autofocus required value=\"";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products_type"] ?? null), "name", array()), "html", null, true);
        echo "\">

          </div>
          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"\" required value=\"";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products_type"] ?? null), "description", array()), "html", null, true);
        echo "\">
          </div>

         
        <button type=\"submit\" class=\"btn btn-primary\">Atualizar</button>
      </form>
      </div>
    </div>
  </div>
</div>




";
    }

    // line 49
    public function block_javascripts($context, array $blocks = array())
    {
        // line 50
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "admin/products_type/edit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 50,  121 => 49,  102 => 34,  94 => 29,  88 => 26,  84 => 25,  70 => 13,  67 => 12,  59 => 8,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  38 => 2,  34 => 1,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Categorias de Produtos - {{ parent() }}{% endblock %}
{% set nav_active = 'role' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li><a href=\"{{ base_url() }}/admin/products_type\">Cargos</a></li>
  <li class=\"active\">{{ products_type.name }}</li>

</ol>
{% endblock %}
{% block content %}

<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Editar Cargo</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"{{ base_url() }}/admin/products_type/update\" method=\"POST\">
        <input type=\"hidden\" name=\"id\" value=\"{{ products_type.id }}\">
        <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"\" title=\"Nome da Categoria\" autofocus required value=\"{{ products_type.name }}\">

          </div>
          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"\" required value=\"{{ products_type.description }}\">
          </div>

         
        <button type=\"submit\" class=\"btn btn-primary\">Atualizar</button>
      </form>
      </div>
    </div>
  </div>
</div>




{% endblock %}
{% block javascripts %}
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
{% endblock %}
", "admin/products_type/edit.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\products_type\\edit.twig");
    }
}
