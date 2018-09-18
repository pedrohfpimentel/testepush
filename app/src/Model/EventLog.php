<?php
 namespace Farol360\Ancora\Model;

class EventLog
{
    public $id;
    public $id_event_log_type;
    public $id_patient;
    public $id_professional;
    public $id_products;
    public $date;
    public $time;
    public $description;


    public function __construct(array $data = [])
    {
        $this->id                  = $data['id'] ?? null;
        $this->id_event_log_type   = $data['id_event_log_type'] ?? null;
        $this->date                = $data['date'] ?? null;
        $this->time                = $data['time'] ?? null;
        $this->description         = $data['description'] ?? null;
        $this->id_patient          = $data['id_patient'] ?? null;
        $this->id_products         = $data['id_products'] ?? null;
        $this->id_professional     = $data['id_professional'] ?? null;
    }
}
