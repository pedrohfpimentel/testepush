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
    public $cancer_type;
    public $discovery_time;
    public $discovery_how;
    public $treatment_time;
    public $treatment_where;
    public $doctor_name;
    public $fundation_need;
    public $visitDate;
    public $registration_date;
    public $doc_ficha;

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
        $this->cancer_type = $data['cancer_type'] ?? null;
        $this->discovery_time = $data['discovery_time'] ?? null;
        $this->discovery_how = $data['discovery_how'] ?? null;
        $this->treatment_time = $data['treatment_time'] ?? null;
        $this->treatment_where = $data['treatment_where'] ?? null;
        $this->doctor_name = $data['doctor_name'] ?? null;
        $this->fundation_need = $data['fundation_need'] ?? null;
        $this->visitDate = $data['visitDate'] ?? null;
        $this->registration_date = $data['registration_date'] ?? null;
        $this->doc_ficha = $data['doc_ficha'] ?? null;
    }
}
