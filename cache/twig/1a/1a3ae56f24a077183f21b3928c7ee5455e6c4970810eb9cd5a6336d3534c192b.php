<?php

/* admin/products/edit.twig */
class __TwigTemplate_e39b778ae1217f95796a5d058b534f660f3a94f0e3b906c946199393a12ec535 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/products/edit.twig", 1);
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
  <li class=\"active\">Editar</li>
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
        echo "
<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Editar Produto</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/products/update\" method=\"POST\">
        <input type=\"hidden\" name=\"id\" value=\"";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products"] ?? null), "id", array()), "html", null, true);
        echo "\">
        <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"Nome\" autofocus required value=\"";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products"] ?? null), "name", array()), "html", null, true);
        echo "\">

          </div>
          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"Descrição\" required value=\"";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["products"] ?? null), "description", array()), "html", null, true);
        echo "\">
          </div>

          
          <div class=\"row\">
            <div class=\"col-sm-10\">
              <div class=\"form-group\">
            <label for=\"selectProductsType\">Categoria do Produto</label>
            <select class=\"form-control\" id=\"selectProductsType\" name=\"id_products_type\">
              ";
        // line 43
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($context["products_type"]);
        foreach ($context['_seq'] as $context["_key"] => $context["products_type"]) {
            // line 44
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
        // line 45
        echo "</select>
          </div>
          </div>
          <div class=\" col-sm-2 form-group\">
              <label for=\"quantity\">Quantidade</label>
              <input type=\"number\" class=\"form-control\" id=\"inputQuantity\" name=\"quantity\" placeholder=\"\">
            </div>
          </div>

        <button type=\"submit\" class=\"btn btn-primary\">Atualizar</button>
      </form>
      </div>
    </div>
  </div>
</div>




";
    }

    // line 65
    public function block_javascripts($context, array $blocks = array())
    {
        // line 66
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "admin/products/edit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  155 => 66,  152 => 65,  129 => 45,  118 => 44,  114 => 43,  102 => 34,  94 => 29,  88 => 26,  84 => 25,  70 => 13,  67 => 12,  60 => 9,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  38 => 2,  34 => 1,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Produtos - Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'role' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li><a href=\"{{ base_url() }}/admin/products\">Produtos</a></li>
  <li class=\"active\">Editar</li>
  <li>{{ products.name }}</li>
</ol>
{% endblock %}
{% block content %}

<div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12\">
        <h3>Editar Produto</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-6\">
        <form role=\"form\" data-toggle=\"validator\" action=\"{{ base_url() }}/admin/products/update\" method=\"POST\">
        <input type=\"hidden\" name=\"id\" value=\"{{ products.id }}\">
        <div class=\"form-group\">
            <label for=\"name\">Nome:</label>
            <input class=\"form-control\" id=\"name\" type=\"text\" name=\"name\" placeholder=\"Nome\" autofocus required value=\"{{ products.name }}\">

          </div>
          <div class=\"form-group\">
            <label for=\"description\">Descrição:</label>
            <input id=\"description\" class=\"form-control\" type=\"text\" name=\"description\" placeholder=\"Descrição\" required value=\"{{ products.description }}\">
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
", "admin/products/edit.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\products\\edit.twig");
    }
}
