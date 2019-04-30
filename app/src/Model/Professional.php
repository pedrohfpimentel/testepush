<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class Professional
{
    public $id;
    public $id_user;
    public $id_professional_type;
    public $name;
    public $email;
    public $cpf;
    public $tel_area;
    public $tel_numero;
    public $end_cep;
    public $professional_type_name;
    public $status;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_user = $data['id_user'] ?? null;
        $this->id_professional_type = $data['id_professional_type'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->tel_area = $data['tel_area'] ?? null;
        $this->tel_numero = $data['tel_numero'] ?? null;
        $this->end_cep = $data['end_cep'] ?? null;
        $this->professional_type_name = $data['professional_type_name'] ?? null;
        $this->status = $data['professional_status'] ?? null;

    }
}
