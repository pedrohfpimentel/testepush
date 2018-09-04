<?php

/* user/signin.twig */
class __TwigTemplate_e706a993d9751714d39203c98dd2126cbf111c96c2e4f23bbe3bdaec86ee2416 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "user/signin.twig", 1);
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
        echo "Entre, por favor - ";
        $this->displayParentBlock("html_title", $context, $blocks);
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<div id=\"signin\">
  <div class=\"container\">
      ";
        // line 6
        if ((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["flash"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5["success"] ?? null) : null)) {
            // line 7
            echo "      <div class=\"alert alert-success alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 9
            echo twig_escape_filter($this->env, (($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["flash"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a["success"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 12
        echo "      ";
        if ((($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["flash"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57["info"] ?? null) : null)) {
            // line 13
            echo "      <div class=\"alert alert-info alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 15
            echo twig_escape_filter($this->env, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["flash"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9["info"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 18
        echo "      ";
        if ((($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 = ($context["flash"] ?? null)) && is_array($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217) || $__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 instanceof ArrayAccess ? ($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217["warning"] ?? null) : null)) {
            // line 19
            echo "      <div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 21
            echo twig_escape_filter($this->env, (($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 = ($context["flash"] ?? null)) && is_array($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105) || $__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 instanceof ArrayAccess ? ($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105["warning"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 24
        echo "      ";
        if ((($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 = ($context["flash"] ?? null)) && is_array($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779) || $__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 instanceof ArrayAccess ? ($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779["danger"] ?? null) : null)) {
            // line 25
            echo "      <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
        ";
            // line 27
            echo twig_escape_filter($this->env, (($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 = ($context["flash"] ?? null)) && is_array($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1) || $__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 instanceof ArrayAccess ? ($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1["danger"] ?? null) : null), "html", null, true);
            echo "
      </div>
      ";
        }
        // line 30
        echo "    </div>
  <div class=\"container\">
    <div class=\"row\">
      <div class=\"col-sm-offset-4 col-sm-4\">
        <form role=\"form\" data-toggle=\"validator\" class=\"form-signin\" action=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/signin\" method=\"POST\">
          <div class=\"form-signin-heading\">
            <h2>Entre, por favor</h2>
            <p class=\"small\">Ainda não possui conta? <a href=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/signup\">Cadastre-se!</a></p>
          </div>
          <label for=\"input-email\" class=\"sr-only\">Email</label>
          <input type=\"email\" name=\"email\" id=\"input-email\" class=\"form-control\" placeholder=\"Email\" required autofocus>
          <label for=\"input-password\" class=\"sr-only\">Senha</label>
          <input type=\"password\" name=\"password\" id=\"input-password\" class=\"form-control\" placeholder=\"Senha\" required>
          ";
        // line 43
        if ((($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0 = ($context["flash"] ?? null)) && is_array($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0) || $__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0 instanceof ArrayAccess ? ($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0["errorLogin"] ?? null) : null)) {
            // line 44
            echo "<div class='alert alert-danger'>";
            echo twig_escape_filter($this->env, (($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866 = ($context["flash"] ?? null)) && is_array($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866) || $__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866 instanceof ArrayAccess ? ($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866["errorLogin"] ?? null) : null), "html", null, true);
            echo "</div>";
        }
        // line 46
        echo "          <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Entrar</button>
          <p class=\"small\">Problemas para se conectar?<br><a href=\"";
        // line 47
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/recover\">Recupere a sua conta.</a></p>
        </form>
      </div>
    </div>

  </div>
</div>";
    }

    // line 56
    public function block_javascripts($context, array $blocks = array())
    {
        // line 57
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "user/signin.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  144 => 57,  141 => 56,  130 => 47,  127 => 46,  122 => 44,  120 => 43,  111 => 37,  105 => 34,  99 => 30,  93 => 27,  89 => 25,  86 => 24,  80 => 21,  76 => 19,  73 => 18,  67 => 15,  63 => 13,  60 => 12,  54 => 9,  50 => 7,  48 => 6,  44 => 4,  41 => 3,  34 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"layout.twig\" %}
{% block html_title %}Entre, por favor - {{ parent() }}{% endblock %}
{% block content -%}
<div id=\"signin\">
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
      <div class=\"col-sm-offset-4 col-sm-4\">
        <form role=\"form\" data-toggle=\"validator\" class=\"form-signin\" action=\"{{ base_url() }}/users/signin\" method=\"POST\">
          <div class=\"form-signin-heading\">
            <h2>Entre, por favor</h2>
            <p class=\"small\">Ainda não possui conta? <a href=\"{{ base_url() }}/users/signup\">Cadastre-se!</a></p>
          </div>
          <label for=\"input-email\" class=\"sr-only\">Email</label>
          <input type=\"email\" name=\"email\" id=\"input-email\" class=\"form-control\" placeholder=\"Email\" required autofocus>
          <label for=\"input-password\" class=\"sr-only\">Senha</label>
          <input type=\"password\" name=\"password\" id=\"input-password\" class=\"form-control\" placeholder=\"Senha\" required>
          {% if(flash['errorLogin']) -%}
          <div class='alert alert-danger'>{{ flash['errorLogin'] }}</div>
          {%- endif %}
          <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Entrar</button>
          <p class=\"small\">Problemas para se conectar?<br><a href=\"{{ base_url() }}/users/recover\">Recupere a sua conta.</a></p>
        </form>
      </div>
    </div>

  </div>
</div>

{%- endblock %}
{% block javascripts %}
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
{% endblock %}
", "user/signin.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\user\\signin.twig");
    }
}
