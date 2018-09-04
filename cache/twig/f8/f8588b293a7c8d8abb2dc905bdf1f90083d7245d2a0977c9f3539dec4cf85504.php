<?php

/* admin/patient/add.twig */
class __TwigTemplate_234b1a993ae9eed37abc827831903a3618a7387d9d564b404c44ca2c7a690014 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin/layout.twig", "admin/patient/add.twig", 1);
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
        $context["nav_active"] = "patients";
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
        echo "/admin/patients\">Pacientes</a></li>
  <li class=\"active\">Adicionar</li>
</ol>
";
    }

    // line 11
    public function block_content($context, array $blocks = array())
    {
        // line 12
        echo "<div class=\"row\">
  <div class=\"col-sm-10\">
    <div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12 \">
        <h3>Adicionar Paciente</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-12\">
        <form role=\"form\" data-toggle=\"validator\" action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/patients/add\" method=\"POST\">

          <div class=\"row\">
            <div class=\"col-sm-6\">
              <div class=\"form-group\">
                <label for=\"inputName\">Nome</label>
                <input type=\"text\" class=\"form-control\" id=\"inputName\" name=\"name\" placeholder=\"\" required>
              </div>
            </div>
            <div class=\"col-sm-6\">
              <div class=\"form-group\">
                <label for=\"inputEmail\">Email</label>
                <input type=\"email\" class=\"form-control\" id=\"inputEmail\" name=\"email\" placeholder=\"\">
                <p id=\"email-error\" style=\"color:red;\"></p>

                <script type=\"text/javascript\">
                  \$('#inputEmail').change(function(){
                    \$('#email-error').html('');

                    if (\$(this).parent().hasClass('has-error')) {

                      \$('#email-error').css('color', 'red');
                      \$('#email-error').html('Utilize um email válido.');
                    } else {
                      //var data = \"{email: \"++\"}\";

                      \$.ajax({
                        url:'";
        // line 52
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        echo "/admin/patients/verifyUserByEmail',
                        type: \"POST\",
                        data: {email: \$('#inputEmail').val()},
                        success: function (data) {
                          console.log(data);

                          if (data == \"true\") {

                            \$('#email-error').css('color', 'red');
                            \$('#email-error').html('o email já está cadastrado.');
                          } else if (data == \"false\") {

                            //\$('#email-error').css('color', 'green');
                            //\$('#email-error').html('email liberado para cadastro.');
                          }
                        },
                        error: function (data) {
                          console.error(data);
                        }

                      });
                    }



                  });
                </script>
              </div>
            </div>
          </div>
          <div class=\"row\">
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputNascimento\">Data de nascimento</label>
              <input type=\"date\" class=\"form-control\" id=\"inputNascimento\" name=\"nascimento\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputCpf\">CPF</label>
              <input type=\"text\" class=\"form-control\" id=\"inputCpf\" name=\"cpf\" placeholder=\"\" title=\"Número do CPF com pontos e traço\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputCpf\">RG</label>
              <input type=\"text\" class=\"form-control\" id=\"inputRg\" name=\"rg\" placeholder=\"\" title=\"Número do RG com pontos e traço\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputCpf\">Cartão SUS</label>
              <input type=\"text\" class=\"form-control\" id=\"inputSus\" name=\"sus\" placeholder=\"\" title=\"Número do SUS com pontos e traço\">
            </div>

          </div>
          <hr>

          <div class=\"row\">

            <div class=\"col-sm-2 \">
              <div class=\"form-group\">
                <label for=\"inputTelArea\">DDD</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelArea\" name=\"tel_area\" placeholder=\"\">
              </div>
            </div>
            <div class=\" col-sm-4 form-group\">
              <label for=\"inputTelefone\">Telefone</label>
              <input type=\"number\" class=\"form-control\" id=\"inputTelefone\" name=\"tel_numero\" placeholder=\"\">
            </div>
             <div class=\"col-sm-6 form-group\">
              <label for=\"inputObservacoes\">Observações</label>
              <input type=\"text\" class=\"form-control\" id=\"inputObservacoes\" name=\"obs_tel\" placeholder=\"\">
            </div>
          </div>
          <div class=\"row\">
            <div class=\"col-sm-2 \">
              <div class=\"form-group\">
                <label for=\"inputTelArea2\">DDD - 2</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelArea\" name=\"tel_area_2\" placeholder=\"\">
              </div>
            </div>
            <div class=\" col-sm-4 form-group\">
              <label for=\"inputTelefone2\">Telefone - 2</label>
              <input type=\"number\" class=\"form-control\" id=\"inputTelefone\" name=\"tel_numero_2\" placeholder=\"\">
            </div>
          </div>
          <hr>
          <div class=\"row\">
            <div class=\"col-sm-2\">
              <div class=\"form-group\">
                <label for=\"inputCep\">CEP</label>
                <input type=\"text\" class=\"form-control\" id=\"inputCep\" name=\"end_cep\" placeholder=\"\" pattern=\"^([0-9]){5}-([0-9]){3}\$\" title=\"Código do CEP com traço\">
              </div>
            </div>

            <div class=\"col-sm-5\">
              <div class=\"form-group\">
                <label for=\"inputRua\">Rua</label>
                <input type=\"text\" class=\"form-control\" id=\"inputRua\" name=\"end_rua\" placeholder=\"\">
              </div>
            </div>
            <div class=\"col-sm-1\">
              <div class=\"form-group\">
                <label for=\"inputNumero\">Número</label>
                <input type=\"text\" class=\"form-control\" id=\"inputNumero\" name=\"end_numero\" placeholder=\"\">
              </div>
            </div>
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"inputComplemento\">Complemento</label>
                <input type=\"text\" class=\"form-control\" id=\"inputComplemento\" name=\"end_complemento\" placeholder=\"\">
              </div>
            </div>
          </div>

          <div class=\"row\">
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"inputBairro\">Bairro</label>
                <input type=\"text\" class=\"form-control\" id=\"inputBairro\" name=\"end_bairro\" placeholder=\"\">
              </div>
            </div>
            <div class=\"col-sm-5 form-group\">
              <label for=\"inputCidade\">Cidade</label>
              <input type=\"text\" class=\"form-control\" id=\"inputCidade\" name=\"end_cidade\" placeholder=\"\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"selectEstado\">Estado</label>
              <select class=\"form-control\" id=\"selectEstado\" name=\"end_estado\">
                <option value=\"\" selected>Estado</option>
                <option value=\"AC\">Acre</option>
                <option value=\"AL\">Alagoas</option>
                <option value=\"AP\">Amapá</option>
                <option value=\"AM\">Amazonas</option>
                <option value=\"BA\">Bahia</option>
                <option value=\"CE\">Ceará</option>
                <option value=\"DF\">Distrito Federal</option>
                <option value=\"ES\">Espírito Santo</option>
                <option value=\"GO\">Goiás</option>
                <option value=\"MA\">Maranhão</option>
                <option value=\"MT\">Mato Grosso</option>
                <option value=\"MS\">Mato Grosso do Sul</option>
                <option value=\"MG\">Minas Gerais</option>
                <option value=\"PA\">Pará</option>
                <option value=\"PB\">Paraíba</option>
                <option value=\"PR\">Paraná</option>
                <option value=\"PE\">Pernambuco</option>
                <option value=\"PI\">Piauí</option>
                <option value=\"RJ\">Rio de Janeiro</option>
                <option value=\"RN\">Rio Grande do Norte</option>
                <option value=\"RS\">Rio Grande do Sul</option>
                <option value=\"RO\">Rondônia</option>
                <option value=\"RR\">Roraima</option>
                <option value=\"SC\">Santa Catarina</option>
                <option value=\"SP\">São Paulo</option>
                <option value=\"SE\">Sergipe</option>
                <option value=\"TO\">Tocantins</option>
              </select>
            </div>
          </div>
          <div class=\"row\">
            <div class=\"col-sm-6\">
              <div class=\"form-group\">
                <label for=\"selectStatus\">Situação</label>
                 <select class=\"form-control\" id=\"selectDisease\" name=\"id_status\">
                  ";
        // line 211
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["patient_status"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["status"]) {
            // line 212
            echo "                  <option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["status"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["status"], "name", array()), "html", null, true);
            echo "
                  </option>
                  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['status'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 215
        echo "                </select>
              </div>
            </div>

          </div>
          <div class=\"row\">
            <div class=\"col-sm-12\">
              <div class=\"form-group\">
                <label for=\"obs\">Observação</label>
                 <input type=\"text\" name=\"obs\" class=\"form-control\">
              </div>
            </div>

          </div>
          <div class=\"row\">
            <div class=\"col-sm-12\">
              <div class=\"form-group\">
                <label for=\"selectDisease\">CID</label>
                <select class=\"form-control\" id=\"selectDisease\" name=\"id_disease\">
                  ";
        // line 234
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["diseases"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["disease"]) {
            // line 235
            echo "                  <option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "cid_version", array()), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "cid_code", array()), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["disease"], "name", array()), "html", null, true);
            echo "</option>
                  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['disease'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 237
        echo "                </select>
              </div>
            </div>

          </div>

          <button type=\"submit\" class=\"btn btn-success\">Adicionar</button>
        </form>
      </div>
    </div>
  </div>
</div>
  </div>
</div>



";
    }

    // line 255
    public function block_javascripts($context, array $blocks = array())
    {
        // line 256
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js\"></script>

<script src=\"";
        // line 258
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
        return "admin/patient/add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  357 => 258,  353 => 256,  350 => 255,  329 => 237,  314 => 235,  310 => 234,  289 => 215,  277 => 212,  273 => 211,  111 => 52,  81 => 25,  66 => 12,  63 => 11,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  38 => 2,  34 => 1,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"admin/layout.twig\" %}
{% block html_title %}Pacientes - Administração - {{ parent() }}{% endblock %}
{% set nav_active = 'patients' %}
{% block breadcrumbs %}
<ol class=\"breadcrumb\">
  <li><a href=\"{{ base_url() }}/admin\">Início</a></li>
  <li><a href=\"{{ base_url() }}/admin/patients\">Pacientes</a></li>
  <li class=\"active\">Adicionar</li>
</ol>
{% endblock %}
{% block content %}
<div class=\"row\">
  <div class=\"col-sm-10\">
    <div class=\"card\">
  <div class=\"card-header\" data-background-color=\"blue\">
    <div class=\"row\">
      <div class=\"col-xs-12 \">
        <h3>Adicionar Paciente</h3>
      </div>
    </div>
  </div>
  <div class=\"card-content\">
    <div class=\"row\">
      <div class=\"col-md-12\">
        <form role=\"form\" data-toggle=\"validator\" action=\"{{ base_url() }}/admin/patients/add\" method=\"POST\">

          <div class=\"row\">
            <div class=\"col-sm-6\">
              <div class=\"form-group\">
                <label for=\"inputName\">Nome</label>
                <input type=\"text\" class=\"form-control\" id=\"inputName\" name=\"name\" placeholder=\"\" required>
              </div>
            </div>
            <div class=\"col-sm-6\">
              <div class=\"form-group\">
                <label for=\"inputEmail\">Email</label>
                <input type=\"email\" class=\"form-control\" id=\"inputEmail\" name=\"email\" placeholder=\"\">
                <p id=\"email-error\" style=\"color:red;\"></p>

                <script type=\"text/javascript\">
                  \$('#inputEmail').change(function(){
                    \$('#email-error').html('');

                    if (\$(this).parent().hasClass('has-error')) {

                      \$('#email-error').css('color', 'red');
                      \$('#email-error').html('Utilize um email válido.');
                    } else {
                      //var data = \"{email: \"++\"}\";

                      \$.ajax({
                        url:'{{ base_url() }}/admin/patients/verifyUserByEmail',
                        type: \"POST\",
                        data: {email: \$('#inputEmail').val()},
                        success: function (data) {
                          console.log(data);

                          if (data == \"true\") {

                            \$('#email-error').css('color', 'red');
                            \$('#email-error').html('o email já está cadastrado.');
                          } else if (data == \"false\") {

                            //\$('#email-error').css('color', 'green');
                            //\$('#email-error').html('email liberado para cadastro.');
                          }
                        },
                        error: function (data) {
                          console.error(data);
                        }

                      });
                    }



                  });
                </script>
              </div>
            </div>
          </div>
          <div class=\"row\">
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputNascimento\">Data de nascimento</label>
              <input type=\"date\" class=\"form-control\" id=\"inputNascimento\" name=\"nascimento\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputCpf\">CPF</label>
              <input type=\"text\" class=\"form-control\" id=\"inputCpf\" name=\"cpf\" placeholder=\"\" title=\"Número do CPF com pontos e traço\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputCpf\">RG</label>
              <input type=\"text\" class=\"form-control\" id=\"inputRg\" name=\"rg\" placeholder=\"\" title=\"Número do RG com pontos e traço\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"inputCpf\">Cartão SUS</label>
              <input type=\"text\" class=\"form-control\" id=\"inputSus\" name=\"sus\" placeholder=\"\" title=\"Número do SUS com pontos e traço\">
            </div>

          </div>
          <hr>

          <div class=\"row\">

            <div class=\"col-sm-2 \">
              <div class=\"form-group\">
                <label for=\"inputTelArea\">DDD</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelArea\" name=\"tel_area\" placeholder=\"\">
              </div>
            </div>
            <div class=\" col-sm-4 form-group\">
              <label for=\"inputTelefone\">Telefone</label>
              <input type=\"number\" class=\"form-control\" id=\"inputTelefone\" name=\"tel_numero\" placeholder=\"\">
            </div>
             <div class=\"col-sm-6 form-group\">
              <label for=\"inputObservacoes\">Observações</label>
              <input type=\"text\" class=\"form-control\" id=\"inputObservacoes\" name=\"obs_tel\" placeholder=\"\">
            </div>
          </div>
          <div class=\"row\">
            <div class=\"col-sm-2 \">
              <div class=\"form-group\">
                <label for=\"inputTelArea2\">DDD - 2</label>
                <input type=\"number\" class=\"form-control\" id=\"inputTelArea\" name=\"tel_area_2\" placeholder=\"\">
              </div>
            </div>
            <div class=\" col-sm-4 form-group\">
              <label for=\"inputTelefone2\">Telefone - 2</label>
              <input type=\"number\" class=\"form-control\" id=\"inputTelefone\" name=\"tel_numero_2\" placeholder=\"\">
            </div>
          </div>
          <hr>
          <div class=\"row\">
            <div class=\"col-sm-2\">
              <div class=\"form-group\">
                <label for=\"inputCep\">CEP</label>
                <input type=\"text\" class=\"form-control\" id=\"inputCep\" name=\"end_cep\" placeholder=\"\" pattern=\"^([0-9]){5}-([0-9]){3}\$\" title=\"Código do CEP com traço\">
              </div>
            </div>

            <div class=\"col-sm-5\">
              <div class=\"form-group\">
                <label for=\"inputRua\">Rua</label>
                <input type=\"text\" class=\"form-control\" id=\"inputRua\" name=\"end_rua\" placeholder=\"\">
              </div>
            </div>
            <div class=\"col-sm-1\">
              <div class=\"form-group\">
                <label for=\"inputNumero\">Número</label>
                <input type=\"text\" class=\"form-control\" id=\"inputNumero\" name=\"end_numero\" placeholder=\"\">
              </div>
            </div>
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"inputComplemento\">Complemento</label>
                <input type=\"text\" class=\"form-control\" id=\"inputComplemento\" name=\"end_complemento\" placeholder=\"\">
              </div>
            </div>
          </div>

          <div class=\"row\">
            <div class=\"col-sm-4\">
              <div class=\"form-group\">
                <label for=\"inputBairro\">Bairro</label>
                <input type=\"text\" class=\"form-control\" id=\"inputBairro\" name=\"end_bairro\" placeholder=\"\">
              </div>
            </div>
            <div class=\"col-sm-5 form-group\">
              <label for=\"inputCidade\">Cidade</label>
              <input type=\"text\" class=\"form-control\" id=\"inputCidade\" name=\"end_cidade\" placeholder=\"\">
            </div>
            <div class=\"col-sm-3 form-group\">
              <label for=\"selectEstado\">Estado</label>
              <select class=\"form-control\" id=\"selectEstado\" name=\"end_estado\">
                <option value=\"\" selected>Estado</option>
                <option value=\"AC\">Acre</option>
                <option value=\"AL\">Alagoas</option>
                <option value=\"AP\">Amapá</option>
                <option value=\"AM\">Amazonas</option>
                <option value=\"BA\">Bahia</option>
                <option value=\"CE\">Ceará</option>
                <option value=\"DF\">Distrito Federal</option>
                <option value=\"ES\">Espírito Santo</option>
                <option value=\"GO\">Goiás</option>
                <option value=\"MA\">Maranhão</option>
                <option value=\"MT\">Mato Grosso</option>
                <option value=\"MS\">Mato Grosso do Sul</option>
                <option value=\"MG\">Minas Gerais</option>
                <option value=\"PA\">Pará</option>
                <option value=\"PB\">Paraíba</option>
                <option value=\"PR\">Paraná</option>
                <option value=\"PE\">Pernambuco</option>
                <option value=\"PI\">Piauí</option>
                <option value=\"RJ\">Rio de Janeiro</option>
                <option value=\"RN\">Rio Grande do Norte</option>
                <option value=\"RS\">Rio Grande do Sul</option>
                <option value=\"RO\">Rondônia</option>
                <option value=\"RR\">Roraima</option>
                <option value=\"SC\">Santa Catarina</option>
                <option value=\"SP\">São Paulo</option>
                <option value=\"SE\">Sergipe</option>
                <option value=\"TO\">Tocantins</option>
              </select>
            </div>
          </div>
          <div class=\"row\">
            <div class=\"col-sm-6\">
              <div class=\"form-group\">
                <label for=\"selectStatus\">Situação</label>
                 <select class=\"form-control\" id=\"selectDisease\" name=\"id_status\">
                  {% for status in patient_status %}
                  <option value=\"{{ status.id }}\">{{ status.name }}
                  </option>
                  {% endfor %}
                </select>
              </div>
            </div>

          </div>
          <div class=\"row\">
            <div class=\"col-sm-12\">
              <div class=\"form-group\">
                <label for=\"obs\">Observação</label>
                 <input type=\"text\" name=\"obs\" class=\"form-control\">
              </div>
            </div>

          </div>
          <div class=\"row\">
            <div class=\"col-sm-12\">
              <div class=\"form-group\">
                <label for=\"selectDisease\">CID</label>
                <select class=\"form-control\" id=\"selectDisease\" name=\"id_disease\">
                  {% for disease in diseases %}
                  <option value=\"{{ disease.id }}\">{{ disease.cid_version }} - {{ disease.cid_code }} - {{ disease.name }}</option>
                  {% endfor %}
                </select>
              </div>
            </div>

          </div>

          <button type=\"submit\" class=\"btn btn-success\">Adicionar</button>
        </form>
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
", "admin/patient/add.twig", "C:\\xampp\\htdocs\\ancora_waldyr_becker\\app\\view\\admin\\patient\\add.twig");
    }
}
