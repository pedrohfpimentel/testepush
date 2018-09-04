<?php

/* admin/dashboard/index.twig */
class __TwigTemplate_ecafa8638d66b37a2d73464d877d1f98dc8da546ee55af811747773747868c9f extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/dashboard/index.twig", 1);
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
        $context["nav_active"] = "dashboard";
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
  <h3>Controle de Paciêntes, Profissionais e Atendimentos.</h3>

  <div class=\"row\">
    <div class=\"col-sm-12\">
      <div class=\"row\">
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"orange\">
              <i class=\"material-icons\">accessibility</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Pacientes Cadastrados</p>
              <h3 class=\"title\">";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["productAmountPublished"] ?? null), "amount", array()), "html", null, true);
        echo "/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["productAmount"] ?? null), "amount", array()), "html", null, true);
        echo "<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"";
        // line 30
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/allmayermais/products\">Ver Produtos</a>
              </div>
            </div>
          </div>
        </div>

        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"orange\">
              <i class=\"material-icons\">portrait</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Profissionais</p>
              <h3 class=\"title\">";
        // line 43
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["productAmountPublished"] ?? null), "amount", array()), "html", null, true);
        echo "/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["productAmount"] ?? null), "amount", array()), "html", null, true);
        echo "<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"";
        // line 47
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/allmayermais/products\">Ver Produtos</a>
              </div>
            </div>
          </div>
        </div>

        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"orange\">
              <i class=\"material-icons\">assignment</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Atendimentos</p>
              <h3 class=\"title\">";
        // line 60
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["productAmountPublished"] ?? null), "amount", array()), "html", null, true);
        echo "/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["productAmount"] ?? null), "amount", array()), "html", null, true);
        echo "<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"";
        // line 64
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/allmayermais/products\">Ver Produtos</a>
              </div>
            </div>
          </div>
        </div>


      </div>
      <h3>Controle de Estoque.</h3>
      <div class=\"row\">
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"green\">
              <i class=\"material-icons\">store</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Fornecedores</p>
              <h3 class=\"title\">";
        // line 81
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["userAmount"] ?? null), "amount", array()), "html", null, true);
        echo "<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"";
        // line 85
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/allmayermais/clients\">Ver Clientes</a>
              </div>
            </div>
          </div>
        </div>
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"green\">
              <i class=\"material-icons\">shopping_basket</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Produtos</p>
              <h3 class=\"title\">";
        // line 97
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["userAmount"] ?? null), "amount", array()), "html", null, true);
        echo "<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"";
        // line 101
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/allmayermais/clients\">Ver Clientes</a>
              </div>
            </div>
          </div>
        </div>
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"green\">
              <i class=\"material-icons\">swap_horiz</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Entradas/Saídas</p>
              <h3 class=\"title\">";
        // line 113
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["userAmount"] ?? null), "amount", array()), "html", null, true);
        echo "<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"";
        // line 117
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/admin/allmayermais/clients\">Ver Clientes</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

";
    }

    public function getTemplateName()
    {
        return "admin/dashboard/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  203 => 117,  196 => 113,  181 => 101,  174 => 97,  159 => 85,  152 => 81,  132 => 64,  123 => 60,  107 => 47,  98 => 43,  82 => 30,  73 => 26,  57 => 12,  54 => 11,  47 => 6,  44 => 5,  37 => 2,  33 => 1,  31 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'dashboard' %}

{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li class=\"active\">Início</li>
</ol>
{% endblock %}

{% block content %}

<div class=\"main\">
  <h3>Controle de Paciêntes, Profissionais e Atendimentos.</h3>

  <div class=\"row\">
    <div class=\"col-sm-12\">
      <div class=\"row\">
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"orange\">
              <i class=\"material-icons\">accessibility</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Pacientes Cadastrados</p>
              <h3 class=\"title\">{{ productAmountPublished.amount }}/{{ productAmount.amount }}<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"{{ base_url }}/admin/allmayermais/products\">Ver Produtos</a>
              </div>
            </div>
          </div>
        </div>

        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"orange\">
              <i class=\"material-icons\">portrait</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Profissionais</p>
              <h3 class=\"title\">{{ productAmountPublished.amount }}/{{ productAmount.amount }}<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"{{ base_url }}/admin/allmayermais/products\">Ver Produtos</a>
              </div>
            </div>
          </div>
        </div>

        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"orange\">
              <i class=\"material-icons\">assignment</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Atendimentos</p>
              <h3 class=\"title\">{{ productAmountPublished.amount }}/{{ productAmount.amount }}<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"{{ base_url }}/admin/allmayermais/products\">Ver Produtos</a>
              </div>
            </div>
          </div>
        </div>


      </div>
      <h3>Controle de Estoque.</h3>
      <div class=\"row\">
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"green\">
              <i class=\"material-icons\">store</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Fornecedores</p>
              <h3 class=\"title\">{{ userAmount.amount }}<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"{{ base_url }}/admin/allmayermais/clients\">Ver Clientes</a>
              </div>
            </div>
          </div>
        </div>
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"green\">
              <i class=\"material-icons\">shopping_basket</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Produtos</p>
              <h3 class=\"title\">{{ userAmount.amount }}<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"{{ base_url }}/admin/allmayermais/clients\">Ver Clientes</a>
              </div>
            </div>
          </div>
        </div>
        <div class=\"col-sm-4\">
          <div class=\"card card-stats\">
            <div class=\"card-header\" data-background-color=\"green\">
              <i class=\"material-icons\">swap_horiz</i>
            </div>
            <div class=\"card-content\">
              <p class=\"category\">Entradas/Saídas</p>
              <h3 class=\"title\">{{ userAmount.amount }}<small> Total</small></h3>
            </div>
            <div class=\"card-footer\">
              <div class=\"stats\">
                <a href=\"{{ base_url }}/admin/allmayermais/clients\">Ver Clientes</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{% endblock %}
", "admin/dashboard/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\dashboard\\index.twig");
    }
}
