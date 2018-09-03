<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class Patient
{
    public $id;
    public $id_user;
    public $id_patient_type;
    public $id_disease;
    public $tel_area_2;
    public $tel_numero_2;
    public $obs_tel;
    public $rg;
    public $sus;
    public $id_status;
    public $obs;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_user = $data['id_user'] ?? null;
        $this->id_patient_type = $data['id_patient_type'] ?? null;
        $this->id_disease = $data['id_disease'] ?? null;
        $this->tel_area_2 = $data['tel_area_2'] ?? null;
        $this->tel_numero_2 = $data['tel_numero_2'] ?? null;
        $this->obs_tel = $data['obs_tel'] ?? null;
        $this->rg = $data['rg'] ?? null;
        $this->sus = $data['sus'] ?? null;
        $this->id_status = $data['id_status'] ?? null;
        $this->obs = $data['obs'] ?? null;
    }
}
