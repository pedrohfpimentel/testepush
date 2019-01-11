<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class Products
{
    public $id;
    public $name;
    public $description;
    public $category;
    public $quantity;
    public $cost;
    public $id_supplier;
    public $patrimony;
    //public $patrimony_code;
   
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->cost = $data['cost'] ?? null;
        $this->id_supplier = $data['id_supplier'] ?? null;
        $this->patrimony = $data['patrimony'] ?? null;
        //$this->patrimony_code = $data['patrimony_code'] ?? null;
     }
}