<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Remessa;

class RemessaSaidaModel extends Model
{
    public function add(Remessa $remessa)
    {
        $sql = "
            INSERT INTO remessa (
                id_product,
                remessa_type,
                quantity,
                cost,
                patrimony_code,
                date,
                time
                )
            VALUES (:id_product, :remessa_type, :quantity, :cost, :patrimony_code, :date, :time)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_product'        => $remessa->id_product,
            ':remessa_type'      => $remessa->remessa_type,
            ':quantity'          => $remessa->quantity,
            ':cost'              => $remessa->cost,
            ':patrimony_code'    => $remessa->patrimony_code,
            ':date'              => $remessa->date,
            ':time'              => $remessa->time

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


      public function deleteByRemessaType(int $id = 99): bool
    {
       $sql = "DELETE FROM remessa WHERE remessa_type = :id";
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



     public function getAllByType(array $id_tipos_remessas = null, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $i_num_params = 0;
        $num_params = [];

        $sql = "
            SELECT
                *
            FROM
                remessa
        ";

        // se houver tipos de remessas, então haverá where
        if ( $id_tipos_remessas != null) {
            $sql .= "WHERE ";

            // i para verificar o primeiro elemento
            $i = 0;

            // percorre o vetor de tipos de remessas.
            foreach ($id_tipos_remessas as $id) {

                // se não for o primeiro
                if ($i > 0) {
                    $sql .= "OR (remessa.remessa_type = ?) ";

                    $num_params[$i_num_params] = $id;
                    $i_num_params++;
                } else {
                    $sql .= "(remessa.remessa_type = ?) ";

                    $num_params[$i_num_params] = $id;
                    $i_num_params++;

                }

                $i++;
            }
        }



        $sql .= "LIMIT ?,?";

        $query = $this->db->prepare($sql);

        foreach ($num_params as $key => $value) {
            $query->bindValue($key+1, $value, \PDO::PARAM_INT);

        }

        $query->bindValue($i_num_params+1, $offset, \PDO::PARAM_INT);
        $query->bindValue($i_num_params+2, $limit, \PDO::PARAM_INT);

        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Remessa::class);
        return $query->fetchAll();
    }

     public function getAmount()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
                remessa

        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }




    public function update(Remessa $remessa): bool
    {
        $sql = "
            UPDATE
                remessa
            SET
                remessa_type   = :remessa_type,
                patient_id     = :patient_id,
                removido     = :removido,
                suppliers      = :suppliers,
                quantity         = :quantity,
                cost             = :cost,
                patrimony_code   = :patrimony_code,
                date             = :date,
                time             = :time


            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id'           => $remessa->id,
            ':remessa_type'   => $remessa->remessa_type,
            ':patient_id' => $remessa->patient_id,
            ':removido' => $remessa->removido,
            ':suppliers' => $remessa->suppliers,
            ':quantity'     => $remessa->quantity,
            ':cost'         => $remessa->cost,
            'patrimony_code' => $remessa->patrimony_code,
            ':date'         => $remessa->date,
            ':time'         => $remessa->time
            ];
        return $query->execute($parameters);
    }

    public function remove(int $remessa)
    {
        $sql = "
            UPDATE
                remessa
            SET
                removido = 1
            WHERE
                id = :id
        ";
        $parameters =
        [':id'   => (int)$remessa];
        $stmt = $this->db->prepare($sql);
        $exec = $stmt->execute($parameters);
    }
}
