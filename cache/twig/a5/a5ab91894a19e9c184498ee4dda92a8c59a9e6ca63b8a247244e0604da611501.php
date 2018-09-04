<?php

/* user/signup.twig */
class __TwigTemplate_6b0120f9dd717253da1a7adef0d6f34d7e4684afd99f5c9625b05c3cb66ca1bb extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "user/signup.twig", 1);
        $this->blocks = array(
            'html_title' => array($this, 'block_html_title'),
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
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_html_title($context, array $blocks = array())
    {
        echo "Crie a sua Conta - ";
        $this->displayParentBlock("html_title", $context, $blocks);
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "<div id=\"signup\">
  <div class=\"container\">
      ";
        // line 7
        if ((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["flash"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5["success"] ?? null) : null)) {
            // line 8
            echo "      <div class=\"alert alert-success alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 10
            echo twig_escape_filter($this->env, (($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["flash"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a["success"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 13
        echo "      ";
        if ((($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["flash"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57["info"] ?? null) : null)) {
            // line 14
            echo "      <div class=\"alert alert-info alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 16
            echo twig_escape_filter($this->env, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["flash"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9["info"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 19
        echo "      ";
        if ((($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 = ($context["flash"] ?? null)) && is_array($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217) || $__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 instanceof ArrayAccess ? ($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217["warning"] ?? null) : null)) {
            // line 20
            echo "      <div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 22
            echo twig_escape_filter($this->env, (($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 = ($context["flash"] ?? null)) && is_array($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105) || $__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 instanceof ArrayAccess ? ($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105["warning"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 25
        echo "      ";
        if ((($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 = ($context["flash"] ?? null)) && is_array($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779) || $__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 instanceof ArrayAccess ? ($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779["danger"] ?? null) : null)) {
            // line 26
            echo "      <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 28
            echo twig_escape_filter($this->env, (($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 = ($context["flash"] ?? null)) && is_array($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1) || $__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 instanceof ArrayAccess ? ($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1["danger"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 31
        echo "    </div>
  <div class=\"container\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-offset-4 col-sm-4\">
        <form role=\"form\" data-toggle=\"validator\" class=\"form-signup\" action=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/signup\" method=\"POST\">
          <div class=\"form-signup-heading\">
            <h2>Crie a sua conta</h2>
            <p class=\"small\">
              Já possui uma conta? <a href=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/signin\">Entre!</a>
            </p>
          </div>
          <label for=\"input-name\" class=\"sr-only\">Nome</label>
          <input type=\"text\" name=\"name\" id=\"input-name\" class=\"form-control\" placeholder=\"Nome\" autofocus required>
          <label for=\"input-email\" class=\"sr-only\">Email</label>
          <input type=\"email\" name=\"email\" id=\"input-email\" class=\"form-control\" placeholder=\"Email\" required>
          <label for=\"input-password\" class=\"sr-only\">Senha</label>
          <input type=\"password\" name=\"password\" id=\"input-password\" class=\"form-control\" placeholder=\"Senha\" required>
          <label for=\"input-confirm-password\" class=\"sr-only\">Confirme a senha</label>
          <input type=\"password\" name=\"confirm_password\" id=\"input-confirm-password\" class=\"form-control\" placeholder=\"Confirme a senha\" required>
          ";
        // line 50
        if ((($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0 = ($context["flash"] ?? null)) && is_array($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0) || $__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0 instanceof ArrayAccess ? ($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0["danger"] ?? null) : null)) {
            // line 51
            echo "<div class='alert alert-danger'>";
            echo twig_escape_filter($this->env, (($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866 = ($context["flash"] ?? null)) && is_array($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866) || $__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866 instanceof ArrayAccess ? ($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866["danger"] ?? null) : null), "html", null, true);
            echo "</div>";
        }
        // line 53
        echo "          <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Cadastrar</button>
        </form>
      </div>
    </div>


  </div>
</div>";
    }

    // line 62
    public function block_javascripts($context, array $blocks = array())
    {
        // line 63
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "user/signup.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 63,  144 => 62,  133 => 53,  128 => 51,  126 => 50,  112 => 39,  105 => 35,  99 => 31,  93 => 28,  89 => 26,  86 => 25,  80 => 22,  76 => 20,  73 => 19,  67 => 16,  63 => 14,  60 => 13,  54 => 10,  50 => 8,  48 => 7,  44 => 5,  41 => 3,  34 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"layout.twig\" %}
{% block html_title %}Crie a sua Conta - {{ parent() }}{% endblock %}
{% block content -%}

<div id=\"signup\">
  <div class=\"container\">
      {% if (flash['success']) %}
      <div class=\"alert alert-success alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        {{ flash['success'] }}
      </div>
      {% endif %}
      {% if (flash['info']) %}
      <div class=\"alert alert-info alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        {{ flash['info'] }}
      </div>
      {% endif %}
      {% if (flash['warning']) %}
      <div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        {{ flash['warning'] }}
      </div>
      {% endif %}
      {% if (flash['danger']) %}
      <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        {{ flash['danger'] }}
      </div>
      {% endif %}
    </div>
  <div class=\"container\">
    <div class=\"row\">
      <div class=\"col-xs-12 col-sm-offset-4 col-sm-4\">
        <form role=\"form\" data-toggle=\"validator\" class=\"form-signup\" action=\"{{ base_url() }}/users/signup\" method=\"POST\">
          <div class=\"form-signup-heading\">
            <h2>Crie a sua conta</h2>
            <p class=\"small\">
              Já possui uma conta? <a href=\"{{ base_url() }}/users/signin\">Entre!</a>
            </p>
          </div>
          <label for=\"input-name\" class=\"sr-only\">Nome</label>
          <input type=\"text\" name=\"name\" id=\"input-name\" class=\"form-control\" placeholder=\"Nome\" autofocus required>
          <label for=\"input-email\" class=\"sr-only\">Email</label>
          <input type=\"email\" name=\"email\" id=\"input-email\" class=\"form-control\" placeholder=\"Email\" required>
          <label for=\"input-password\" class=\"sr-only\">Senha</label>
          <input type=\"password\" name=\"password\" id=\"input-password\" class=\"form-control\" placeholder=\"Senha\" required>
          <label for=\"input-confirm-password\" class=\"sr-only\">Confirme a senha</label>
          <input type=\"password\" name=\"confirm_password\" id=\"input-confirm-password\" class=\"form-control\" placeholder=\"Confirme a senha\" required>
          {% if(flash['danger']) -%}
          <div class='alert alert-danger'>{{ flash['danger'] }}</div>
          {%- endif %}
          <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Cadastrar</button>
        </form>
      </div>
    </div>


  </div>
</div>
{%- endblock %}
{% block javascripts %}
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
{% endblock %}
", "user/signup.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\user\\signup.twig");
    }
}
