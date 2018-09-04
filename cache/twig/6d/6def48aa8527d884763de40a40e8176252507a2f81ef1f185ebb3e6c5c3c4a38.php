<?php

/* admin/professional/history.twig */
class __TwigTemplate_373dfe6cd2bf1e74dd16e44aee61c39b30d1cca8608488ed97ab2a3848d52a82 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/professional/history.twig", 1);
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
        $context["nav_active"] = "professionals";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_html_title($context, array $blocks = array())
    {
        echo "Pacientes - Administração - ";
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
        echo "/admin/professionals\">Profissionais</a></li>
  <li class=\"active\">Histórico</li>
  <li >";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["professional"] ?? null), "name", array()), "html", null, true);
        echo "</li>
</ol>
";
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "<div class=\"row\">
  <div class=\"col-sm-12\">
    <div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12 \">
        <h3>Histórico de Profissional - ";
        // line 19
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["professional"] ?? null), "name", array()), "html", null, true);
        echo "</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-12\">
        <table class=\"table table-striped data-table\">
          <thead>
            <th>Data</th>
            <th>Tipo</th>
            <th>Descrição</th>
          </thead>
          <tbody>
            ";
        // line 33
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["event_logs"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["event_log"]) {
            // line 34
            echo "              <tr>
                <td>";
            // line 35
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event_log"], "date", array()), "html", null, true);
            echo "</td>
                <td>";
            // line 36
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event_log"], "event_log_types_name", array()), "html", null, true);
            echo "</td>
                <td>";
            // line 37
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event_log"], "description", array()), "html", null, true);
            echo "</td>
              </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 40
            echo "              <div class=\"alert alert-danger\" role=\"alert\">Não foram encontrados eventos para este usuário.</div>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['event_log'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
  </div>
</div>



";
    }

    // line 54
    public function block_javascripts($context, array $blocks = array())
    {
        // line 55
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>

<script src=\"";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/js/jquery.mask.js\"></script>


<script type=\"text/javascript\">

\$('#inputCpf').mask('000.000.000-00', {reverse: true});
\$('#inputCep').mask('00000-000');
</script>

";
    }

    public function getTemplateName()
    {
        return "admin/professional/history.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  148 => 57,  144 => 55,  141 => 54,  126 => 42,  119 => 40,  111 => 37,  107 => 36,  103 => 35,  100 => 34,  95 => 33,  78 => 19,  70 => 13,  67 => 12,  60 => 9,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  38 => 2,  34 => 1,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Pacientes - Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'professionals' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li><a href=\"{{ base_url() }}/admin/professionals\">Profissionais</a></li>
  <li class=\"active\">Histórico</li>
  <li >{{ professional.name }}</li>
</ol>
{% endblock %}
{% block content %}
<div class=\"row\">
  <div class=\"col-sm-12\">
    <div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12 \">
        <h3>Histórico de Profissional - {{ professional.name }}</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-12\">
        <table class=\"table table-striped data-table\">
          <thead>
            <th>Data</th>
            <th>Tipo</th>
            <th>Descrição</th>
          </thead>
          <tbody>
            {% for event_log in event_logs %}
              <tr>
                <td>{{ event_log.date }}</td>
                <td>{{ event_log.event_log_types_name }}</td>
                <td>{{ event_log.description }}</td>
              </tr>
            {% else %}
              <div class=\"alert alert-danger\" role=\"alert\">Não foram encontrados eventos para este usuário.</div>
            {% endfor %}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
  </div>
</div>



{% endblock %}
{% block javascripts %}
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>

<script src=\"{{ base_url() }}/js/jquery.mask.js\"></script>


<script type=\"text/javascript\">

\$('#inputCpf').mask('000.000.000-00', {reverse: true});
\$('#inputCep').mask('00000-000');
</script>

{% endblock %}
", "admin/professional/history.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\professional\\history.twig");
    }
}
