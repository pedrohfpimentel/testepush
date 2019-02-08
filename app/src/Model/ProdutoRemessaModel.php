<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\ProdutoRemessa;

class ProdutoRemessaModel extends Model
{
    public function add(ProdutoRemessa $produto_remessa)
    {
        $sql = "
            INSERT INTO produto_remessa (
            id_product,
            id_remessa,
            patrimony_code,
            cost,
            quantity
            )  
        VALUES (:id_product, :id_remessa, :patrimony_code, :cost, :quantity)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_product'        => $produto_remessa->id_product,
            ':id_remessa'        => $produto_remessa->id_remessa,
            ':patrimony_code'    => $produto_remessa->patrimony_code,
            ':cost'              => $produto_remessa->cost,
            ':quantity'          => $produto_remessa->quantity
        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
      $sql = "DELETE FROM produto_remessa WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        return $query->execute($parameters);
    }

    public function get(int $id)
    {
     $sql = "
            SELECT
                *
            FROM
                produto_remessa
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Products::class);
        return $query->fetch();   
    }

    
    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                produto_remessa
            ORDER BY
                id
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Attendance::class);
        return $query->fetchAll();
    }


     public function getAmount()
    {  
    }



    public function update(ProdutoRemessa $produto_remessa): bool
    {
        
    }
}
