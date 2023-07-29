<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class Volunteer
{
    public $id;
    public $name;
    public $email;
    public $nascimento;
    public $cpf;
    public $tel_area;
    public $tel_numero;
    public $end_cep;
    public $end_rua;
    public $end_numero;
    public $end_complemento;
    public $end_bairro;
    public $end_cidade;
    public $end_estado;
    public $obs;
    public $status;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->nascimento = $data['nascimento'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->tel_area = $data['tel_area'] ?? null;
        $this->tel_numero = $data['tel_numero'] ?? null;
        $this->end_cep = $data['end_cep'] ?? null;
        $this->end_rua = $data['end_rua'] ?? null;
        $this->end_numero = $data['end_numero'] ?? null;
        $this->end_complemento = $data['end_complemento'] ?? null;
        $this->end_bairro = $data['end_bairro'] ?? null;
        $this->end_cidade = $data['end_cidade'] ?? null;
        $this->end_estado = $data['end_estado'] ?? null;
        $this->obs = $data['obs'] ?? null;
        $this->status = $data['status'] ?? null;

    }
}
