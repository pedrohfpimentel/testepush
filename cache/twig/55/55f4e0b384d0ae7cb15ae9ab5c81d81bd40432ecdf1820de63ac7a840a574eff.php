<?php

/* page/index.twig */
class __TwigTemplate_ec8777f148f3039824f34f80653047122c22810548817ee776bb269c29f34d18 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "page/index.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["nav_active"] = "index";
        // line 3
        $context["nav_active"] = "inicio";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 7
        echo "<section class=\"\" >
    <div class=\"container\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <h2>Olá!</h2>
          <p>Faça login para acessar a administração do sistema. </p>

        </div>

      </div>
    </div>
  </section>";
    }

    // line 21
    public function block_javascripts($context, array $blocks = array())
    {
        echo " ";
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "
";
    }

    public function getTemplateName()
    {
        return "page/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 21,  41 => 7,  38 => 5,  34 => 1,  32 => 3,  30 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"layout.twig\" %}
{% set nav_active = 'index' %}
{% set nav_active = 'inicio' %}

{% block content -%}

  <section class=\"\" >
    <div class=\"container\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <h2>Olá!</h2>
          <p>Faça login para acessar a administração do sistema. </p>

        </div>

      </div>
    </div>
  </section>

{%- endblock %}
{% block javascripts %} {{ parent()}}
{% endblock %}
", "page/index.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\page\\index.twig");
    }
}
