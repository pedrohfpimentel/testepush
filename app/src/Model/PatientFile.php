<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class PatientFile
{
    public $id;
    public $id_patient;
    public $name;
    public $url_file;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_patient = $data['id_patient'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->url_file = $data['url_file'] ?? null;
    }
}
