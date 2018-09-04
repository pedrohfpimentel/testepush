<?php

/* admin/products_type/add.twig */
class __TwigTemplate_8d93990191a5b9c058070c0cd9c6ea1ea658337dc5cbaa0c8ff4b714d250da07 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/products_type/add.twig", 1);
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
        echo "/admin/products_type\">Categorias de Produtos</a></li>
  <li class=\"active\">Adicionar</li>
</ol>
";
    }

    // line 11
    public function block_content($context, array $blocks = array())
    {
        // line 12
        echo "<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Adicionar Categoria</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/products_type/add\" method=\"POST\">
          <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"\" title=\"Nome da Categoria\" autofocus required>

          </div>
          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"\" required>
          </div>

          

          <button type=\"submit\" class=\"btn btn-success\">Adicionar</button>
        </form>
      </div>
    </div>
  </div>
</div>


";
    }

    // line 45
    public function block_javascripts($context, array $blocks = array())
    {
        // line 46
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>


";
    }

    public function getTemplateName()
    {
        return "admin/products_type/add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 46,  105 => 45,  79 => 23,  66 => 12,  63 => 11,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  38 => 2,  34 => 1,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Categorias de Produtos - {{ parent() }}{% endblock %}
{% set nav_active = 'role' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li><a href=\"{{ base_url() }}/admin/products_type\">Categorias de Produtos</a></li>
  <li class=\"active\">Adicionar</li>
</ol>
{% endblock %}
{% block content %}
<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Adicionar Categoria</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"{{ base_url() }}/admin/products_type/add\" method=\"POST\">
          <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"\" title=\"Nome da Categoria\" autofocus required>

          </div>
          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"\" required>
          </div>

          

          <button type=\"submit\" class=\"btn btn-success\">Adicionar</button>
        </form>
      </div>
    </div>
  </div>
</div>


{% endblock %}
{% block javascripts %}
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>


{% endblock %}
", "admin/products_type/add.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\products_type\\add.twig");
    }
}
