<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\RemessaType;

class RemessaTypeModel extends Model
{
    public function add(RemessaType $remessaType)
    {
        $sql = "
            INSERT INTO remessa_type (
                slug,
                name,
                description
                )
            VALUES (
                :slug,
                :name,
                :description)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':slug'              => $remessaType->slug,
            ':name'              => $remessaType->name,
            ':description'       => $remessaType->description
        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM remessa_type WHERE id = :id";
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
                remessa_type
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, RemessaType::class);
        return $query->fetch();
    }

    public function getBySlug(string $slug)
    {
        $sql = "
            SELECT
                *
            FROM
                remessa_type
            WHERE
                slug = :slug
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':slug' => $slug];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Remessatype::class);
        return $query->fetch();
    }

    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                remessa_type
            ORDER BY
                id
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, RemessaType::class);
        return $query->fetchAll();
    }

    public function update(RemessaType $remessaType): bool
    {
        $sql = "
            UPDATE
                remessa_type
            SET
                slug        = :slug,
                name        = :name,
                description = :description
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':slug'        => $remessaType->slug,
            ':name'        => $remessaType->name,
            ':description' => $remessaType->description,
            ':id'          => $remessaType->id
        ];
        return $query->execute($parameters);
    }
}
