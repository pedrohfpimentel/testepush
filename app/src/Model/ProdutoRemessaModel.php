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
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, ProdutoRemessa::class);
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
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, ProdutoRemessa::class);
        return $query->fetchAll();
    }

    public function getAllByRemessa(int $id_remessa, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                produto_remessa.*,
                products.name as name_product
            FROM
                produto_remessa
                LEFT JOIN products ON produto_remessa.id_product = products.id
            WHERE
                produto_remessa.id_remessa = ?
            ORDER BY
                produto_remessa.id
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $id_remessa, \PDO::PARAM_INT);
        $query->bindValue(2, $offset, \PDO::PARAM_INT);
        $query->bindValue(3, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, ProdutoRemessa::class);
        return $query->fetchAll();
    }

    public function getAllByRemessaId(int $id_remessa, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                produto_remessa.id_product
            FROM
                produto_remessa
            WHERE
                id_remessa = ?
            ORDER BY
                id
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $id_remessa, \PDO::PARAM_INT);
        $query->bindValue(2, $offset, \PDO::PARAM_INT);
        $query->bindValue(3, $limit, \PDO::PARAM_INT);
        $query->execute();
        //$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, ProdutoRemessa::class);
        return $query->fetchAll();
    }

    public function getAllByProduct(int $id_product, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                produto_remessa.*,
                remessa.remessa_type as remessa_type
            FROM
                produto_remessa
                LEFT JOIN remessa ON remessa.id = produto_remessa.id_remessa
            WHERE
                produto_remessa.id_product = ?
            ORDER BY
                produto_remessa.id
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $id_product, \PDO::PARAM_INT);
        $query->bindValue(2, $offset, \PDO::PARAM_INT);
        $query->bindValue(3, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, ProdutoRemessa::class);
        return $query->fetchAll();
    }

    public function getAllByProduct2(int $id_product, string $start, string $finish): array
    {
        $sql = "
            SELECT
                produto_remessa.*,
                remessa.remessa_type as remessa_type
            FROM
                produto_remessa
                LEFT JOIN remessa ON remessa.id = produto_remessa.id_remessa
            WHERE
                (produto_remessa.id_product = ?) AND (remessa.date BETWEEN ? AND ?)
            ORDER BY
                produto_remessa.id
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $id_product, \PDO::PARAM_INT);
        $query->bindValue(2, $start, \PDO::PARAM_STR);
        $query->bindValue(3, $finish, \PDO::PARAM_STR);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, ProdutoRemessa::class);
        return $query->fetchAll();
    }


     public function getAmount()
    {
    }



    public function update(ProdutoRemessa $produto_remessa): bool
    {

    }
}
