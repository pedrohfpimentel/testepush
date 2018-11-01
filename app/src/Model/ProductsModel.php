<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Products;

class ProductsModel extends Model
{
    public function add(Products $products)
    {
        $sql = "
            INSERT INTO products (
                name,
                description,
                category
                )
            VALUES (:name, :description, :category)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':name'          => $products->name,
            ':description'   => $products->description,
            ':category'      => $products->category

        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM products WHERE id = :id";
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
                products
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

    public function getAll(): array
    {
        $sql = "
            SELECT
               products.*,
               products_type.name as products_type_name
            FROM
                products
                LEFT JOIN products_type ON products.category = products_type.id
            ORDER BY
                id ASC
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Products::class);
        return $query->fetchAll();
    }

    public function getRemessasByIdProduct($id_product): array
    {
        $sql = "
            SELECT
                *
            FROM
                remessa
            WHERE 
                id_product =:id_product
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id_product' => $id_product];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Remessa::class);
        return $query->fetchAll();
    }

    public function getProductID(): array
    {
        $sql = "
            SELECT
                id
            FROM
                product
        ";
        $query = $this->db->prepare($sql);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Product::class);
        return $query->fetchAll();
    }

    public function update(Products $products): bool
    {
        $sql = "
            UPDATE
                products
            SET
                name         = :name,
                description  = :description,
                category     = :category

            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id'           => $products->id,
            ':name'         => $products->name,
            ':description'  => $products->description,
            ':category'     => $products->category
            ];
        return $query->execute($parameters);
    }
}
