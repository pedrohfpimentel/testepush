<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class Remessa
{
    public $id;
    public $id_product;
    public $id_remessa_type;
    public $quantity;
    public $cost;
    public $date;
    public $time;


    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_product = $data['id_product'] ?? null;
        $this->id_remessa_type = $data['id_remessa_type'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->cost = $data['cost'] ?? null;
        $this->date = $data['date'] ?? null;
        $this->time = $data['time'] ?? null;

     }
}
