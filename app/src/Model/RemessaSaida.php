<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class RemessaSaida
{
    public $id;
    public $id_product;
    public $id_remessa_type;
    public $remessa_type;
    public $quantity;
    public $cost;
    public $date;
    public $time;
    public $patrimony_code;


    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_product = $data['id_product'] ?? null;
        $this->id_remessa_type = $data['id_remessa_type'] ?? null;
        $this->remessa_type = $data['remessa_type'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->cost = $data['cost'] ?? null;
        $this->date = $data['date'] ?? null;
        $this->time = $data['time'] ?? null;
        $this->patrimony_code = $data['patrimony_code'] ?? null;

     }
}
