<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\EventLog;

class EventLogModel extends Model
{
    public function add(EventLog $eventLog)
    {
        $sql = "
            INSERT INTO event_logs (
                event_log_type,
                date,
                time,
                description,
                id_patient,
                suppliers,
                id_products,
                id_professional,
                id_remessa,
                id_remessa_saida
                )
            VALUES (
                :event_log_type,
                :date,
                :time,
                :description,
                :id_patient,
                :suppliers,
                :id_products,
                :id_professional,
                :id_remessa,
                :id_remessa_saida
                )
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':event_log_type'       => $eventLog->event_log_type,
            ':date'                 => $eventLog->date,
            ':time'                 => $eventLog->time,
            ':description'          => $eventLog->description,
            ':id_patient'           => $eventLog->id_patient,
            ':suppliers'          => $eventLog->suppliers,
            ':id_products'          => $eventLog->id_products,
            ':id_professional'      => $eventLog->id_professional,
            ':id_remessa'           => $eventLog->id_remessa,
            ':id_remessa_saida'     => $eventLog->id_remessa_saida

        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM event_logs WHERE id = :id";
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
                event_logs
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetch();
    }

    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                event_logs
            ORDER BY
                date
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByPatient(int $id)
    {
        $sql = "
            SELECT
                event_logs.id as id,
                event_logs.event_log_type,
                event_logs.id_patient,
                event_logs.suppliers,
                event_logs.id_products,
                event_logs.id_professional,
                event_logs.id_remessa,
                event_logs.id_remessa_saida,
                event_logs.date,
                event_logs.time,
                event_logs.description,
                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description,
                patients.id as patients_id,
                patients.id_user as patients_id_user,
                users.name as users_name,
                users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
                LEFT JOIN patients ON patients.id = event_logs.id_patient
                LEFT JOIN suppliers ON suppliers.id = event_logs.suppliers
                LEFT JOIN users ON patients.id_user = users.id
            WHERE
                id_patient = :id,
                suppliers = :id

        ";
        
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    

    public function getByProducts(int $id)
    {
        $sql = "
            SELECT
                event_logs.id as id,
                event_logs.event_log_type,
                event_logs.id_patient,
                event_logs.suppliers,
                event_logs.id_products,
                event_logs.id_professional,
                event_logs.id_remessa,
                event_logs.id_remessa_saida,
                event_logs.date,
                event_logs.time,
                event_logs.description,
               
                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description
              #  products.id as products_id
              #  products.id_user as products_id_user,
              #  users.name as users_name
              #  users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
               # LEFT JOIN products ON products.id = event_logs.products
               # LEFT JOIN users ON products.id = users.id
            WHERE
                id_products = :id

        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByProfessional(int $id)
    {
       $sql = "
            SELECT
                event_logs.id as id,
                event_logs.event_log_type,
                event_logs.id_patient,
                event_logs.suppliers,
                event_logs.id_professional,
                event_logs.id_remessa,
                event_logs.id_remessa_saida,
                event_logs.date,
                event_logs.time,
                event_logs.description,
               
                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description,
                professionals.id as professionals_id,
                professionals.id_user as professionals_id_user,
                users.name as users_name,
                users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
                LEFT JOIN professionals ON professionals.id = event_logs.id_professional
                LEFT JOIN users ON professionals.id_user = users.id
            WHERE
                id_professional = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByRemessa(int $id)
    {
        $sql = "
            SELECT
                event_logs.id as id,
                event_logs.event_log_type,
                event_logs.id_patient,
                event_logs.suppliers,
                event_logs.id_products,
                event_logs.id_professional,
                event_logs.id_remessa,
                 event_logs.id_remessa_saida,
                event_logs.date,
                event_logs.time,
                event_logs.description,
               
                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description
              #  products.id as products_id
              #  products.id_user as products_id_user,
              #  users.name as users_name
              #  users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
               # LEFT JOIN products ON products.id = event_logs.products
               # LEFT JOIN users ON products.id = users.id
            WHERE
                id_products = :id

        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByRemessaSaida(int $id)
    {
        $sql = "
            SELECT
                event_logs.id as id,
                event_logs.event_log_type,
                event_logs.id_patient,
                event_logs.suppliers,
                event_logs.id_products,
                event_logs.id_professional,
                event_logs.id_remessa,
                event_logs.id_remessa_saida,
                event_logs.date,
                event_logs.time,
                event_logs.description,
               
                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description
              #  products.id as products_id
              #  products.id_user as products_id_user,
              #  users.name as users_name
              #  users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
               # LEFT JOIN products ON products.id = event_logs.products
               # LEFT JOIN users ON products.id = users.id
            WHERE
                id_products = :id

        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function update(EventLog $eventLog): bool
    {
        $sql = "
            UPDATE
                event_logs
            SET
                event_log_type      = :event_log_type,
                date                = :date,
                time                = :time,
                description         = :description,
               
                id_patient          = :id_patient,
                suppliers         = :suppliers,
                id_products         = :id_products,
                id_professional     = :id_professional,
                id_remessa          = :id_remessa,
                id_remessa_saida          = :id_remessa_saida
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':event_log_type'=> $eventLog->event_log_type,
            ':date'             => $eventLog->date,
            ':time'             => $eventLog->time,
            ':description'      => $eventLog->description,
            
            ':id_patient'       => $eventLog->id_patient,
            ':suppliers'      => $eventLog->suppliers,
            ':id_products'      => $eventLog->id_products,
            ':id_professional'  => $eventLog->id_professional,
            ':id_remessa'       => $eventLog->id_remessa,
            ':id_remessa_saida'       => $eventLog->id_remessa_saida,
            ':id'               => $eventLog->id
        ];
        return $query->execute($parameters);
    }
}
