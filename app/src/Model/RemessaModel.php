<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Remessa;
use Farol360\Ancora\Model\ProdutoRemessa;

class RemessaModel extends Model
{
    public function add(Remessa $remessa)
    {
        $sql = "
            INSERT INTO remessa (
                id_product,
                suppliers,
                remessa_type,
                quantity,
                cost,
                patrimony_code,
                date,
                time
                )
            VALUES (:id_product, :suppliers, :remessa_type, :quantity, :cost, :patrimony_code, :date, :time)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_product'        => $remessa->id_product,
            ':suppliers'         => $remessa->suppliers,
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

    public function addPatient(Remessa $remessa)
    {
        $sql = "
            INSERT INTO remessa (
                id_product,
                suppliers,
                remessa_type,
                quantity,
                cost,
                patrimony_code,
                patient_id,
                date,
                time
                )
            VALUES (:id_product, :suppliers, :remessa_type, :quantity, :cost, :patrimony_code, :patient_id, :date, :time)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_product'        => $remessa->id_product,
            ':suppliers'         => $remessa->suppliers,
            ':remessa_type'      => $remessa->remessa_type,
            ':quantity'          => $remessa->quantity,
            ':cost'              => $remessa->cost,
            ':patrimony_code'    => $remessa->patrimony_code,
            ':id_product'        => $remessa->id_product,
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
        // seleciona todas as remessas que tem remessa_type de acordo com o id passado..
        // retorna uma lista de id
        $sql = "SELECT id FROM remessa WHERE remessa_type = :id";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $remessas_id = $query->execute($parameters);
        $remessas_id = $query->fetchAll();

        //var_dump($remessas_id);
        //die;
        // para cada $remessas_id delete produto_remessas.
        foreach( $remessas_id as $remessa_id) {
            $sql = "DELETE FROM produto_remessa WHERE id_remessa = :id";
            $query = $this->db->prepare($sql);
            $parameters = [':id' => $remessa_id->id];
            $query->execute($parameters);
        }

        // deleta a remessa de acordo com o remessa_type passado
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
    public function getRemovido(int $id)
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


    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                remessa
            ORDER BY
                id
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
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
            WHERE (remessa.remessa_type = 1) OR (remessa.remessa_type = 2) OR (remessa.remessa_type = 3) OR (remessa.remessa_type = 6) OR (remessa.remessa_type = 7)
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public function getAmountSaida()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
                remessa
            WHERE (remessa.remessa_type = 4) OR (remessa.remessa_type = 5) OR (remessa.remessa_type = 8)
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    public function getRemessaDownload($remessa_start, $remessa_finish)
    {
        $sql = "
        SELECT
            event_logs.date,
            remessa.*
        FROM
            remessa,
            event_logs

        WHERE
            event_logs.date BETWEEN :remessa_start AND :remessa_finish
    ";
    $query = $this->db->prepare($sql);

    $params = [':remessa_start' => $remessa_start, ':remessa_finish' => $remessa_finish];
    $query->execute($params);
    return $query->fetchAll();
    }




    public function getAllByDate(string $start, string $finish, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
          $sql = "
            SELECT
                *
            FROM
                remessa
        WHERE
           remessa.date BETWEEN ? AND ?
        ORDER BY
            date DESC

        LIMIT ? , ?
    ";
    $query = $this->db->prepare($sql);
    $query->bindValue(1, $start, \PDO::PARAM_STR);
    $query->bindValue(2, $finish, \PDO::PARAM_STR);
    $query->bindValue(3, $offset, \PDO::PARAM_INT);
    $query->bindValue(4, $limit, \PDO::PARAM_INT);
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
        $sql .= "ORDER BY
                date DESC
                LIMIT ?,?";

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
    public function getAllByStatus( int $type = 1, string $start, string $finish, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                remessa
            WHERE
                remessa.remessa_type =  ?
                AND (remessa.date BETWEEN ? AND ?)
                ORDER BY
                    date DESC
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $type, \PDO::PARAM_INT);
        $query->bindValue(2, $start, \PDO::PARAM_STR);
        $query->bindValue(3, $finish, \PDO::PARAM_STR);
        $query->bindValue(4, $offset, \PDO::PARAM_INT);
        $query->bindValue(5, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Remessa::class);
        return $query->fetchAll();



    }

    public function getAllByProduct(int $id_product, string $start, string $finish): array
    {
        $sql = "
            SELECT
                remessa.*,
                produto_remessa.*
            FROM
                remessa,
                produto_remessa
            WHERE
                produto_remessa.id_product =  ?
                AND (remessa.date BETWEEN ? AND ?)
                ORDER BY
                    date DESC
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $id_product, \PDO::PARAM_INT);
        $query->bindValue(2, $start, \PDO::PARAM_STR);
        $query->bindValue(3, $finish, \PDO::PARAM_STR);
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
                suppliers        = :suppliers,
                patient_id       = :patient_id,
                removido         = :removido,
                remessa_type     = :remessa_type,
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
            ':suppliers'    => $remessa->suppliers,
            ':patient_id'   => $remessa->patient_id,
            ':removido'     => $remessa->removido,
            ':remessa_type' => $remessa->remessa_type,
            ':quantity'     => $remessa->quantity,
            ':cost'         => $remessa->cost,
            'patrimony_code' => $remessa->patrimony_code,
            ':date'         => $remessa->date,
            ':time'         => $remessa->time
            ];
        return $query->execute($parameters);
    }


    public function updatePatient(Remessa $remessa): bool
    {
        $sql = "
            UPDATE
                remessa
            SET
                suppliers        = :suppliers,
                remessa_type     = :remessa_type,
                quantity         = :quantity,
                cost             = :cost,
                patrimony_code   = :patrimony_code,
                patient_id       = :patient_id,
                removido         = :removido,
                date             = :date,
                time             = :time

            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id'           => $remessa->id,
            ':suppliers'    => $remessa->suppliers,
            ':remessa_type' => $remessa->remessa_type,
            ':quantity'     => $remessa->quantity,
            ':cost'         => $remessa->cost,
            'patrimony_code' => $remessa->patrimony_code,
            'patient_id'    => $remessa->patient_id,
            'removido'    => $remessa->removido,
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
