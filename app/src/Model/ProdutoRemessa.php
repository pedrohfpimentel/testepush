<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class ProdutoRemessa
{
    public $id;
    public $id_product;
    public $id_remessa;
    public $patrimony_code;
    public $cost;
    public $quantity;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_product = $data['id_product'] ?? null;
        $this->id_remessa = $data['id_remessa'] ?? null;
        $this->patrimony_code = $data['patrimony_code'] ?? null;
        $this->cost = $data['cost'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        
    }
}
