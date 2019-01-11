<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Supplier;

class SupplierModel extends Model
{
    public function add(Supplier $supplier)
    {
        $sql = "
            INSERT INTO suppliers (
                name,
                description,
                email,
                ddd,
                telefone
                )
            VALUES (:name, :description, :email, :ddd, :telefone)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':name'          => $supplier->name,
            ':description'   => $supplier->description,
            ':email'         => $supplier->email,
            ':ddd'           => $supplier->ddd,
            ':telefone'      => $supplier->telefone,

        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM suppliers WHERE id = :id";
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
                suppliers
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Supplier::class);
        return $query->fetch();
    }

    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
               *
            FROM
                suppliers
            ORDER BY
                id ASC
                LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Supplier::class);
        return $query->fetchAll();
    }


    public function getAllDownload(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *

            FROM
                suppliers
            ORDER BY
                suppliers.id ASC

        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Suppliers::class);
        return $query->fetchAll();
    }

     public function getAmount()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
                suppliers

        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public function update(Supplier $supplier): bool
    {
        $sql = "
            UPDATE
                suppliers
            SET
                name         = :name,
                description  = :description,
                email        = :email,
                ddd          = :ddd,
                telefone     = :telefone
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id'           => $supplier->id,
            ':name'         => $supplier->name,
            ':description'  => $supplier->description,
            ':email'     => $supplier->email,
            ':ddd'          => $supplier->ddd,
            ':telefone'     => $supplier->telefone
        ];
        return $query->execute($parameters);
    }
}
