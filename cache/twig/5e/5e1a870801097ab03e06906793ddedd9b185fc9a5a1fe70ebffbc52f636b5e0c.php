<?php

/* admin/products/add.twig */
class __TwigTemplate_cf4894895ab06200da5364a91157d7563003380c20833d3f91e5e6295e3d3846 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/products/add.twig", 1);
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
        $context["nav_active"] = "products";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_html_title($context, array $blocks = array())
    {
        echo "Produtos - Administração - ";
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
        echo "/admin/products\">Produtos</a></li>
  <li class=\"active\">Adicionar</li>
   <li>";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products"] ?? null), "name", array()), "html", null, true);
        echo "</li>
</ol>
";
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Adicionar Produto</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/products/add\" method=\"POST\">
          <input type=\"hidden\" name=\"id\" value=\"";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products"] ?? null), "id", array()), "html", null, true);
        echo "\">
          <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"Nome\" autofocus required>
          </div>

          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"Descrição\" required>
          </div>

          <div class=\"row\">
            <div class=\"col-sm-10\">
              <div class=\"form-group\">
            <label for=\"selectProductsType\">Categoria do Produto</label>
            <select class=\"form-control\" id=\"selectProductsType\" name=\"id_products_type\">
              ";
        // line 41
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($context["products_type"]);
        foreach ($context['_seq'] as $context["_key"] => $context["products_type"]) {
            // line 42
            echo "              <option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["products_type"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["products_type"], "name", array()), "html", null, true);
            echo "</option>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['products_type'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "</select>
          </div>
          </div>
            <div class=\" col-sm-2 form-group\">
              <label for=\"quantity\">Quantidade</label>
              <input type=\"number\" class=\"form-control\" id=\"inputQuantity\" name=\"quantity\" placeholder=\"\">
            </div>
          </div>

          <button type=\"submit\" class=\"btn btn-success\">Adicionar</button>
        </form>
      </div>
    </div>
  </div>
</div>


";
    }

    // line 61
    public function block_javascripts($context, array $blocks = array())
    {
        // line 62
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>

";
    }

    public function getTemplateName()
    {
        return "admin/products/add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  145 => 62,  142 => 61,  121 => 43,  110 => 42,  106 => 41,  87 => 25,  83 => 24,  70 => 13,  67 => 12,  60 => 9,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  38 => 2,  34 => 1,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Produtos - Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'products' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li><a href=\"{{ base_url() }}/admin/products\">Produtos</a></li>
  <li class=\"active\">Adicionar</li>
   <li>{{ products.name }}</li>
</ol>
{% endblock %}
{% block content %}
<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Adicionar Produto</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"{{ base_url() }}/admin/products/add\" method=\"POST\">
          <input type=\"hidden\" name=\"id\" value=\"{{ products.id }}\">
          <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"Nome\" autofocus required>
          </div>

          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"Descrição\" required>
          </div>

          <div class=\"row\">
            <div class=\"col-sm-10\">
              <div class=\"form-group\">
            <label for=\"selectProductsType\">Categoria do Produto</label>
            <select class=\"form-control\" id=\"selectProductsType\" name=\"id_products_type\">
              {% for products_type in products_type %}
              <option value=\"{{ products_type.id }}\">{{ products_type.name }}</option>
              {% endfor %}</select>
          </div>
          </div>
            <div class=\" col-sm-2 form-group\">
              <label for=\"quantity\">Quantidade</label>
              <input type=\"number\" class=\"form-control\" id=\"inputQuantity\" name=\"quantity\" placeholder=\"\">
            </div>
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
", "admin/products/add.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\products\\add.twig");
    }
}
