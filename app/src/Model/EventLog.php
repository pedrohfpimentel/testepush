<?php
 namespace Farol360\Ancora\Model;

class EventLog
{
    public $id;
    public $event_log_type;
    public $id_patient;
    public $id_professional;
    public $id_products;
    public $id_remessa;
    public $id_remessa_saida;
    public $date;
    public $time;
    public $description;
    public $quantity;


    public function __construct(array $data = [])
    {
        $this->id                  = $data['id'] ?? null;
        $this->event_log_type      = $data['event_log_type'] ?? null;
        $this->date                = $data['date'] ?? null;
        $this->time                = $data['time'] ?? null;
        $this->description         = $data['description'] ?? null;
        $this->quantity            = $data['quantity'] ?? null;
        $this->id_patient          = $data['id_patient'] ?? null;
        $this->id_products         = $data['id_products'] ?? null;
        $this->id_professional     = $data['id_professional'] ?? null;
        $this->id_remessa          = $data['id_remessa'] ?? null;
        $this->id_remessa_saida    = $data['id_remessa_saida'] ?? null;
    }
}
