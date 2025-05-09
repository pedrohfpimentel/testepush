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
                id_remessa_saida,
                product_list,
                id_attendance
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
                :id_remessa_saida,
                :product_list,
                :id_attendance
                )
        ";
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
            ':id_remessa_saida'     => $eventLog->id_remessa_saida,
            ':product_list'          => $eventLog->product_list,
            ':id_attendance'        => $eventLog->id_attendance

        ];
        $query = $this->db->prepare($sql);
        // $exec = $query->execute($parameters);
        if ($query->execute($parameters)) {
            // $data['status'] = true;
            $data['data'] = $this->db->lastInsertId();
            $data['errorCode'] = null;
            $data['errorInfo'] = null;
            // return $this->db->lastInsertId();
        } else {
            // return null;
            // $data['status'] = false;
            $data['data'] = false;
            $data['errorCode'] = $query->errorCode();
            $data['errorInfo'] = $query->errorInfo();
        }
        // $data['status'] = $exec;
        $data['table'] = 'event_log';
        $data['function'] = 'add';
        $modelReturn = new ModelReturn($data);
        return $modelReturn;
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

    public function getByPatientExport(int $id, string $start, string $finish, int $search)
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
                event_logs.product_list,
                event_logs.id_attendance,
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

                LEFT JOIN users ON patients.id_user = users.id
            WHERE
                id_patient = :id AND (event_logs.date BETWEEN :start AND :finish) ";
            if ($search == 1) {
                $sql .="ORDER BY event_logs.date ASC";
            }
            if ($search == 2) {
                $sql .="ORDER BY event_logs.date DESC";
            }
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id, ':start' => $start, ':finish' => $finish];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByPatient(int $id, int $search)
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
                event_logs.product_list,
                event_logs.id_attendance,
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

                LEFT JOIN users ON patients.id_user = users.id
            WHERE
                id_patient = :id ";
            if ($search == 1) {
                $sql .="ORDER BY
                 event_logs.date ASC";
            }
            if ($search == 2) {
                $sql .="ORDER BY
                 event_logs.date DESC";
            }
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }



    public function getByProducts(int $id, int $search, $cod_patrimonio = '')
    {//var_dump($cod_patrimonio);die;
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
                event_logs.product_list,

                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description,
                remessa.date as remessa_date,
                produto_remessa.patrimony_code as patrimony_code
              #  products.id as products_id
              #  products.id_user as products_id_user,
              #  users.name as users_name
              #  users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
                LEFT JOIN remessa ON event_logs.id_remessa = remessa.id
                LEFT JOIN produto_remessa ON produto_remessa.id_remessa = remessa.id
               # LEFT JOIN products ON products.id = event_logs.products
               # LEFT JOIN users ON products.id = users.id
            WHERE
                ( id_products = :id
                OR
                event_logs.product_list LIKE CONCAT('%', :id2, '%'))  ";
            if($cod_patrimonio != ''){
                $sql .=" AND produto_remessa.patrimony_code = :cod_patrimonio ";
            }
            if ($search == 1) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, event_logs.date ASC";
            }
            if ($search == 2) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, event_logs.date DESC";
            }
            if ($search == 3) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, remessa.date ASC";
            }
            if ($search == 4) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, remessa.date DESC";
            }


        $query = $this->db->prepare($sql);
        if($cod_patrimonio != ''){
        $parameters = [':id' => $id, ':id2' => '"'.$id.'"', ':cod_patrimonio' => $cod_patrimonio];
        } else {
            $parameters = [':id' => $id, ':id2' => '"'.$id.'"'];

        }
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByProducts2(int $id, string $start, string $finish, int $search, $cod_patrimonio = '')
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
                event_logs.product_list,

                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description,
                remessa.date as remessa_date,
                remessa.remessa_type as remessa_type,
                produto_remessa.patrimony_code as patrimony_code
              #  products.id as products_id
              #  products.id_user as products_id_user,
              #  users.name as users_name
              #  users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
                LEFT JOIN remessa ON event_logs.id_remessa = remessa.id
                LEFT JOIN produto_remessa ON produto_remessa.id_remessa = remessa.id
               # LEFT JOIN products ON products.id = event_logs.products
               # LEFT JOIN users ON products.id = users.id
            WHERE
                (id_products = :id
                OR
                event_logs.product_list LIKE CONCAT('%', :id2, '%') AND (event_logs.date BETWEEN :start AND :finish)) ";
            if($cod_patrimonio != ''){
                $sql .=" AND produto_remessa.patrimony_code = :cod_patrimonio ";
            }
            if ($search == 1) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, event_logs.date ASC";
            }
            if ($search == 2) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, event_logs.date DESC";
            }
            if ($search == 3) {
                $sql .="ORDER BY
                 CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, remessa.date ASC";
            }
            if ($search == 4) {
                $sql .="ORDER BY
                CASE WHEN remessa.date IS NULL THEN 1 ELSE 0 END, remessa.date DESC";
            }


        $query = $this->db->prepare($sql);
        if($cod_patrimonio != ''){
            $parameters = [':id' => $id, ':id2' => '"'.$id.'"', ':start' => $start, ':finish' => $finish, ':cod_patrimonio' => $cod_patrimonio];
        } else {
            $parameters = [':id' => $id, ':id2' => '"'.$id.'"', ':start' => $start, ':finish' => $finish];
        }
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByProductsExport(int $id, string $start, string $finish)
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
                event_logs.product_list,

                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description,
                remessa.date as remessa_date
              #  products.id as products_id
              #  products.id_user as products_id_user,
              #  users.name as users_name
              #  users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
                LEFT JOIN remessa ON event_logs.id_remessa = remessa.id
               # LEFT JOIN products ON products.id = event_logs.products
               # LEFT JOIN users ON products.id = users.id
            WHERE
                (id_products = :id OR event_logs.product_list LIKE CONCAT('%', :id2, '%')) ";
                if (remessa.date == null) {
                    $sql .= " AND (event_logs.date BETWEEN :start AND :finish) ";
                } else {
                    $sql .= " AND (remessa.date BETWEEN :start AND :finish) ";
                }
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id, ':id2' => '"'.$id.'"', ':start' => $start, ':finish' => $finish];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }


    public function getAllByProductList($id)
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
                event_logs.product_list,

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
                event_logs.product_list LIKE CONCAT('%', ?, '%')

        ";
        $query = $this->db->prepare($sql);
        //$parameters = [':id' => $id];
        $query->bindValue(1, '"'.$id.'"', \PDO::PARAM_STR);

        $query->execute();
        //var_dump($query);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }

    public function getByProfessional(int $id, int $search)
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
                id_professional = :id ";
                if ($search == 1) {
                $sql .="ORDER BY
                 event_logs.date ASC";
            }
            if ($search == 2) {
                $sql .="ORDER BY
                 event_logs.date DESC";
            }

        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, EventLog::class);
        return $query->fetchAll();
    }



    public function getByProfessionalNamePatient(int $id, string $start, string $finish, int $search)
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
                patients.id as patients_id,
                patients.id_user as patients_id_user,
                users.name as users_name,
                users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.event_log_type = event_log_types.id
                LEFT JOIN professionals ON professionals.id = event_logs.id_professional
                LEFT JOIN patients ON patients.id = event_logs.id_patient
                LEFT JOIN users ON patients.id_user = users.id

            WHERE
                id_professional = :id AND (event_logs.date BETWEEN :start AND :finish)
        ";
        if ($search == 1) {
                $sql .="ORDER BY event_logs.date ASC";
            }
            if ($search == 2) {
                $sql .="ORDER BY event_logs.date DESC";
            }
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id, ':start' => $start, ':finish' => $finish];
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
