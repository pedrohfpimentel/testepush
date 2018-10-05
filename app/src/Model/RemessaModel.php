<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Remessa;

class RemessaModel extends Model
{
    public function add(Remessa $remessa)
    {
        $sql = "
            INSERT INTO remessa (
                id_product,
                id_remessa_type,
                quantity,
                cost,
                date,
                time
                )
            VALUES (
                :id_product,
                :id_remessa_type,
                :quantity,
                :cost,
                :date,
                :time
                )
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_product'       => $remessa->id_product,
            ':id_remessa_type'   => $remessa->id_remessa_type,
            ':quantity'          => $remessa->quantity,
            ':cost'              => $remessa->cost,
            ':date'              => $remessa->date,
            ':time'              => $remessa->time,

        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM remessa WHERE id = :id";
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
                remessa
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Remessa::class);
        return $query->fetch();
    }

    public function getAll(): array
    {
        $sql = "
            SELECT
               *
            FROM
                remessa
            ORDER BY
                id ASC
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Remessa::class);
        return $query->fetchAll();
    }

    public function update(Remessa $remessa): bool
    {
        $sql = "
            UPDATE
                remessa
            SET
                id_product       = :id_product,
                id_remessa_type  = :id_remessa_type,
                quantity         = :quantity,
                cost             = :cost,
                date             = :date,
                time             = :time

            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id'               => $remessa->id,
            ':id_product'       => $remessa->id_product,
            ':id_remessa_type'  => $remessa->id_remessa_type,
            ':quantity'     => $remessa->quantity,
            ':cost'         => $remessa->cost,
            ':date'         => $remessa->date,
            ':time'         => $remessa->time
            ];
        return $query->execute($parameters);
    }
}
