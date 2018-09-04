<?php

/* 404.twig */
class __TwigTemplate_d293cb8babf1c1bb075fee2a759f7f32428f81ecc2b015bb8db6141bfc170742 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "404.twig", 1);
        $this->blocks = array(
            'html_title' => array($this, 'block_html_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_html_title($context, array $blocks = array())
    {
        echo "Página não encontrada - Erro 404 - ";
        $this->displayParentBlock("html_title", $context, $blocks);
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "<div class=\"container\">
  <h1>Página não encontrada</h1>
  <h2 class=\"text-center\">Erro 404</h2>
  <p class=\"text-center\">
    A página que você está procurando não pode ser encontrada.<br>
    Confira a barra de endereço para verificar se sua URL foi digitada corretamente.
  </p>
</div>";
    }

    public function getTemplateName()
    {
        return "404.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 5,  40 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"layout.twig\" %}
{% block html_title %}Página não encontrada - Erro 404 - {{ parent() }}{% endblock %}
{% block content -%}

<div class=\"container\">
  <h1>Página não encontrada</h1>
  <h2 class=\"text-center\">Erro 404</h2>
  <p class=\"text-center\">
    A página que você está procurando não pode ser encontrada.<br>
    Confira a barra de endereço para verificar se sua URL foi digitada corretamente.
  </p>
</div>

{%- endblock %}
", "404.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\404.twig");
    }
}
