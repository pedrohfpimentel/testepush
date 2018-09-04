<?php

/* user/profile.twig */
class __TwigTemplate_86eb00e06f831de9a9d219910251520804b33d88f5b43c01e33a4ee220c63c6b extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "user/profile.twig", 1);
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
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "name", array()), "html", null, true);
        echo " - ";
        $this->displayParentBlock("html_title", $context, $blocks);
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<div class=\"container-profile container\">
  <div class=\"card\">
    <div class=\"card-header\" data-background-color=\"blue\">
      <h3>Perfil</h3>
    </div>
    <div class=\"card-content\">
      <div class=\"row\">
        <div class=\"col-xs-12 col-sm-12\">
          ";
        // line 12
        if ((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["flash"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5["success"] ?? null) : null)) {
            // line 13
            echo "            <div class=\"alert alert-success alert-dismissible\" role=\"alert\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
              ";
            // line 15
            echo twig_escape_filter($this->env, (($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["flash"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a["success"] ?? null) : null), "html", null, true);
            echo "
            </div>
            ";
        }
        // line 18
        echo "            ";
        if ((($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["flash"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57["info"] ?? null) : null)) {
            // line 19
            echo "            <div class=\"alert alert-info alert-dismissible\" role=\"alert\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
              ";
            // line 21
            echo twig_escape_filter($this->env, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["flash"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9["info"] ?? null) : null), "html", null, true);
            echo "
            </div>
            ";
        }
        // line 24
        echo "            ";
        if ((($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 = ($context["flash"] ?? null)) && is_array($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217) || $__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 instanceof ArrayAccess ? ($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217["warning"] ?? null) : null)) {
            // line 25
            echo "            <div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
              ";
            // line 27
            echo twig_escape_filter($this->env, (($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 = ($context["flash"] ?? null)) && is_array($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105) || $__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 instanceof ArrayAccess ? ($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105["warning"] ?? null) : null), "html", null, true);
            echo "
            </div>
            ";
        }
        // line 30
        echo "            ";
        if ((($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 = ($context["flash"] ?? null)) && is_array($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779) || $__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 instanceof ArrayAccess ? ($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779["danger"] ?? null) : null)) {
            // line 31
            echo "            <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>
              ";
            // line 33
            echo twig_escape_filter($this->env, (($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 = ($context["flash"] ?? null)) && is_array($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1) || $__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 instanceof ArrayAccess ? ($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1["danger"] ?? null) : null), "html", null, true);
            echo "
            </div>
          ";
        }
        // line 36
        echo "        </div>
      </div>
      <div class=\"row\">
        <div class=\"col-xs-12 col-sm-7 \">
          <form role=\"form\" data-toggle=\"validator\" action=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/users/profile\" method=\"POST\">
            <input type=\"hidden\" name=\"id\" value=\"";
        // line 41
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "id", array()), "html", null, true);
        echo "\">
            <div class=\"row\">
              <div class=\"col-sm-12\">

                <div class=\"form-group\">
                  <label for=\"inputName\">Nome</label>
                  <input type=\"text\" class=\"form-control\" id=\"inputName\" name=\"name\" placeholder=\"Nome\" value=\"";
        // line 47
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "name", array()), "html", null, true);
        echo "\" data-minlength=\"5\" required>
                </div>
              </div>

              <div class=\"col-sm-12\">
                <div class=\"form-group\">
                  <label for=\"inputEmail\">Email</label>
                  <input type=\"email\" class=\"form-control\" id=\"inputEmail\" name=\"email\" placeholder=\"Email\" value=\"";
        // line 54
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", array()), "html", null, true);
        echo "\" required>
                </div>
              </div>
              <div class=\"col-sm-6 form-group\">
                <label for=\"inputNascimento\">Data de nascimento</label>
                <input type=\"date\" class=\"form-control\" id=\"inputNascimento\" name=\"nascimento\" value=\"";
        // line 59
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "nascimento", array()), "html", null, true);
        echo "\">
              </div>
              <div class=\"col-sm-6 form-group\">
                <label for=\"inputCpf\">CPF</label>
                <input type=\"text\" class=\"form-control\" id=\"inputCpf\" name=\"cpf\" placeholder=\"CPF\" value=\"";
        // line 63
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "cpf", array()), "html", null, true);
        echo "\" pattern=\"^([0-9]){3}\\.([0-9]){3}\\.([0-9]){3}-([0-9]){2}\$\" title=\"Número do CPF com pontos e traço\">
              </div>
            </div>
            <div class=\"row\">


              <div class=\"col-sm-3 form-group\">
                <label for=\"inputTelArea\">DDD</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelArea\" name=\"tel_area\" placeholder=\"DDD\" value=\"";
        // line 71
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "tel_area", array()), "html", null, true);
        echo "\">
              </div>
              <div class=\"col-sm-9 form-group\">
                <label for=\"inputTelefone\">Telefone</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelefone\" name=\"tel_numero\" placeholder=\"Telefone\" value=\"";
        // line 75
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "tel_numero", array()), "html", null, true);
        echo "\">
              </div>
            </div>
            <div class=\"row\">

            </div>
            <hr>
            ";
        // line 154
        echo "            <div class=\"row\">
              <div class=\"col-xs-12 col-sm-5\">
                <div class=\"form-group\">
                  <label for=\"inputNewPassword\">Nova senha</label>
                  <input type=\"password\" class=\"form-control\" id=\"inputNewPassword\" name=\"new_password\" placeholder=\"Nova senha\">
                </div>
              </div>
              <div class=\"col-xs-12 col-sm-offset-1 col-sm-6\">
                <div class=\"form-group\">
                  <div class=\"well\">
                    <label for=\"inputCurrentPassword\">Senha atual</label>
                    <input type=\"password\" class=\"form-control\" id=\"inputCurrentPassword\"
                      name=\"password\" placeholder=\"Senha atual\" aria-describedby=\"helpBlock\" required>
                    <span id=\"helpBlock\" class=\"help-block\">Por favor, coloque sua senha atual para confirmar as alterações.</span>
                    <div class=\"form-group\">
                      <button type=\"submit\" class=\"btn btn-info\">Salvar alterações</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class=\"row\">

            </div>
          </form>
        </div>
        <div class=\"col-xs-12 col-sm-offset-1 col-sm-4\">
          <div class=\"card\">

            <div class=\"card-content\">
              <div class=\"row\">
                <div class=\"col-xs-12 col-sm-12\">

                  <a href=\"\" class=\"btn btn-lg btn-default\" style=\"width:100%;\">Contato com Suporte</a>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</div>";
    }

    // line 203
    public function block_javascripts($context, array $blocks = array())
    {
        // line 204
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "user/profile.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  232 => 204,  229 => 203,  178 => 154,  168 => 75,  161 => 71,  150 => 63,  143 => 59,  135 => 54,  125 => 47,  116 => 41,  112 => 40,  106 => 36,  100 => 33,  96 => 31,  93 => 30,  87 => 27,  83 => 25,  80 => 24,  74 => 21,  70 => 19,  67 => 18,  61 => 15,  57 => 13,  55 => 12,  45 => 4,  42 => 3,  34 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"layout.twig\" %}
{% block html_title %}{{ user.name }} - {{ parent() }}{% endblock %}
{% block content -%}
<div class=\"container-profile container\">
  <div class=\"card\">
    <div class=\"card-header\" data-background-color=\"blue\">
      <h3>Perfil</h3>
    </div>
    <div class=\"card-content\">
      <div class=\"row\">
        <div class=\"col-xs-12 col-sm-12\">
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
      </div>
      <div class=\"row\">
        <div class=\"col-xs-12 col-sm-7 \">
          <form role=\"form\" data-toggle=\"validator\" action=\"{{ base_url() }}/users/profile\" method=\"POST\">
            <input type=\"hidden\" name=\"id\" value=\"{{ user.id }}\">
            <div class=\"row\">
              <div class=\"col-sm-12\">

                <div class=\"form-group\">
                  <label for=\"inputName\">Nome</label>
                  <input type=\"text\" class=\"form-control\" id=\"inputName\" name=\"name\" placeholder=\"Nome\" value=\"{{ user.name }}\" data-minlength=\"5\" required>
                </div>
              </div>

              <div class=\"col-sm-12\">
                <div class=\"form-group\">
                  <label for=\"inputEmail\">Email</label>
                  <input type=\"email\" class=\"form-control\" id=\"inputEmail\" name=\"email\" placeholder=\"Email\" value=\"{{ user.email }}\" required>
                </div>
              </div>
              <div class=\"col-sm-6 form-group\">
                <label for=\"inputNascimento\">Data de nascimento</label>
                <input type=\"date\" class=\"form-control\" id=\"inputNascimento\" name=\"nascimento\" value=\"{{ user.nascimento }}\">
              </div>
              <div class=\"col-sm-6 form-group\">
                <label for=\"inputCpf\">CPF</label>
                <input type=\"text\" class=\"form-control\" id=\"inputCpf\" name=\"cpf\" placeholder=\"CPF\" value=\"{{ user.cpf }}\" pattern=\"^([0-9]){3}\\.([0-9]){3}\\.([0-9]){3}-([0-9]){2}\$\" title=\"Número do CPF com pontos e traço\">
              </div>
            </div>
            <div class=\"row\">


              <div class=\"col-sm-3 form-group\">
                <label for=\"inputTelArea\">DDD</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelArea\" name=\"tel_area\" placeholder=\"DDD\" value=\"{{ user.tel_area }}\">
              </div>
              <div class=\"col-sm-9 form-group\">
                <label for=\"inputTelefone\">Telefone</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelefone\" name=\"tel_numero\" placeholder=\"Telefone\" value=\"{{ user.tel_numero }}\">
              </div>
            </div>
            <div class=\"row\">

            </div>
            <hr>
            {#
            <div class=\"row\">
              <div class=\"col-sm-4\">
                <div class=\"form-group\">
                  <label for=\"inputCep\">CEP</label>
                  <input type=\"text\" class=\"form-control\" id=\"inputCep\" name=\"end_cep\" placeholder=\"CEP\" value=\"{{ user.end_cep }}\" pattern=\"^([0-9]){5}-([0-9]){3}\$\" title=\"Código do CEP com traço\">
                </div>
              </div>
            </div>
            <div class=\"row\">
              <div class=\"col-sm-8 form-group\">
                <label for=\"inputRua\">Rua</label>
                <input type=\"text\" class=\"form-control\" id=\"inputRua\" name=\"end_rua\" placeholder=\"Rua\" value=\"{{ user.end_rua }}\">
              </div>
              <div class=\"col-sm-3 form-group\">
                <label for=\"inputNumero\">Número</label>
                <input type=\"text\" class=\"form-control\" id=\"inputNumero\" name=\"end_numero\" placeholder=\"Número\" value=\"{{ user.end_numero }}\">
              </div>
            </div>
            <div class=\"row\">
              <div class=\"col-sm-6\">
                <div class=\"form-group\">
                  <label for=\"inputComplemento\">Complemento</label>
                  <input type=\"text\" class=\"form-control\" id=\"inputComplemento\" name=\"end_complemento\" placeholder=\"Complemento\" value=\"{{ user.end_complemento }}\">
                </div>
              </div>
              <div class=\"col-sm-5\">
                <div class=\"form-group\">
                  <label for=\"inputBairro\">Bairro</label>
                  <input type=\"text\" class=\"form-control\" id=\"inputBairro\" name=\"end_bairro\" placeholder=\"Bairro\" value=\"{{ user.end_bairro }}\">
                </div>
              </div>
              <div class=\"col-sm-7 form-group\">
                <label for=\"inputCidade\">Cidade</label>
                <input type=\"text\" class=\"form-control\" id=\"inputCidade\" name=\"end_cidade\" placeholder=\"Cidade\" value=\"{{ user.end_cidade }}\">
              </div>
              <div class=\"col-sm-4 form-group\">
                <label for=\"selectEstado\">Estado</label>
                <select class=\"form-control\" id=\"selectEstado\" name=\"end_estado\">
                  <option value=\"\"{{ user.end_estado is empty ? ' selected' }}>Estado</option>
                  <option value=\"AC\"{{ user.end_estado == 'AC' ? ' selected' }}>Acre</option>
                  <option value=\"AL\"{{ user.end_estado == 'AL' ? ' selected' }}>Alagoas</option>
                  <option value=\"AP\"{{ user.end_estado == 'AP' ? ' selected' }}>Amapá</option>
                  <option value=\"AM\"{{ user.end_estado == 'AM' ? ' selected' }}>Amazonas</option>
                  <option value=\"BA\"{{ user.end_estado == 'BA' ? ' selected' }}>Bahia</option>
                  <option value=\"CE\"{{ user.end_estado == 'CE' ? ' selected' }}>Ceará</option>
                  <option value=\"DF\"{{ user.end_estado == 'DF' ? ' selected' }}>Distrito Federal</option>
                  <option value=\"ES\"{{ user.end_estado == 'ES' ? ' selected' }}>Espírito Santo</option>
                  <option value=\"GO\"{{ user.end_estado == 'GO' ? ' selected' }}>Goiás</option>
                  <option value=\"MA\"{{ user.end_estado == 'MA' ? ' selected' }}>Maranhão</option>
                  <option value=\"MT\"{{ user.end_estado == 'MT' ? ' selected' }}>Mato Grosso</option>
                  <option value=\"MS\"{{ user.end_estado == 'MS' ? ' selected' }}>Mato Grosso do Sul</option>
                  <option value=\"MG\"{{ user.end_estado == 'MG' ? ' selected' }}>Minas Gerais</option>
                  <option value=\"PA\"{{ user.end_estado == 'PA' ? ' selected' }}>Pará</option>
                  <option value=\"PB\"{{ user.end_estado == 'PB' ? ' selected' }}>Paraíba</option>
                  <option value=\"PR\"{{ user.end_estado == 'PR' ? ' selected' }}>Paraná</option>
                  <option value=\"PE\"{{ user.end_estado == 'PE' ? ' selected' }}>Pernambuco</option>
                  <option value=\"PI\"{{ user.end_estado == 'PI' ? ' selected' }}>Piauí</option>
                  <option value=\"RJ\"{{ user.end_estado == 'RJ' ? ' selected' }}>Rio de Janeiro</option>
                  <option value=\"RN\"{{ user.end_estado == 'RN' ? ' selected' }}>Rio Grande do Norte</option>
                  <option value=\"RS\"{{ user.end_estado == 'RS' ? ' selected' }}>Rio Grande do Sul</option>
                  <option value=\"RO\"{{ user.end_estado == 'RO' ? ' selected' }}>Rondônia</option>
                  <option value=\"RR\"{{ user.end_estado == 'RR' ? ' selected' }}>Roraima</option>
                  <option value=\"SC\"{{ user.end_estado == 'SC' ? ' selected' }}>Santa Catarina</option>
                  <option value=\"SP\"{{ user.end_estado == 'SP' ? ' selected' }}>São Paulo</option>
                  <option value=\"SE\"{{ user.end_estado == 'SE' ? ' selected' }}>Sergipe</option>
                  <option value=\"TO\"{{ user.end_estado == 'TO' ? ' selected' }}>Tocantins</option>
                </select>
              </div>
            </div>
            <hr>
            #}
            <div class=\"row\">
              <div class=\"col-xs-12 col-sm-5\">
                <div class=\"form-group\">
                  <label for=\"inputNewPassword\">Nova senha</label>
                  <input type=\"password\" class=\"form-control\" id=\"inputNewPassword\" name=\"new_password\" placeholder=\"Nova senha\">
                </div>
              </div>
              <div class=\"col-xs-12 col-sm-offset-1 col-sm-6\">
                <div class=\"form-group\">
                  <div class=\"well\">
                    <label for=\"inputCurrentPassword\">Senha atual</label>
                    <input type=\"password\" class=\"form-control\" id=\"inputCurrentPassword\"
                      name=\"password\" placeholder=\"Senha atual\" aria-describedby=\"helpBlock\" required>
                    <span id=\"helpBlock\" class=\"help-block\">Por favor, coloque sua senha atual para confirmar as alterações.</span>
                    <div class=\"form-group\">
                      <button type=\"submit\" class=\"btn btn-info\">Salvar alterações</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class=\"row\">

            </div>
          </form>
        </div>
        <div class=\"col-xs-12 col-sm-offset-1 col-sm-4\">
          <div class=\"card\">

            <div class=\"card-content\">
              <div class=\"row\">
                <div class=\"col-xs-12 col-sm-12\">

                  <a href=\"\" class=\"btn btn-lg btn-default\" style=\"width:100%;\">Contato com Suporte</a>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</div>
{%- endblock %}
{% block javascripts %}
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>
{% endblock %}
", "user/profile.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\user\\profile.twig");
    }
}
