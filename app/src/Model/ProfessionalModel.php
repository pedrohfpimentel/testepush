<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Professional;

class ProfessionalModel extends Model
{
    public function add(Professional $professional)
    {
        $sql = "
            INSERT INTO professionals (
                id_user,
                id_professional_type
                )
            VALUES (:id_user, :id_professional_type)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_user'          => $professional->id_user,
            ':id_professional_type' => $professional->id_professional_type
        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM professionals WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        return $query->execute($parameters);
    }

    public function get(int $id)
    {
        $sql = "
            SELECT
                users.*,
                professionals.*
            FROM
                professionals LEFT JOIN users ON users.id = professionals.id_user

            WHERE
                professionals.id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Professional::class);
        return $query->fetch();
    }

    public function getByEmail(string $email) {
        $sql = "
            SELECT
                *
            FROM
                users
            WHERE
                email = :email
            LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = [':email' => $email];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Professionals::class);
        return $query->fetch();
    }

    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                users.*,
                professionals.*,
                users.id as id_user,
                users.name as user_name,
                users.email as user_email,
                professional_types.name as professional_type_name

            FROM
                professionals
                LEFT JOIN users ON users.id = professionals.id_user
                LEFT JOIN professional_types ON professional_types.id = professionals.id_professional_type
            ORDER BY
                professionals.id ASC

        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Professionals::class);
        return $query->fetchAll();
    }


      public function getAmount()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
                professionals

        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


      public function getProfessionalsDownload($professionals_start, $professionals_finish)
    {
        $sql = "
        SELECT 
            event_logs.date,
            professionals.*
        FROM
            professionals,
            event_logs

        WHERE
            event_logs.date BETWEEN :professionals_start AND :professionals_finish
    ";
    $query = $this->db->prepare($sql);

    $params = [':professionals_start' => $professionals_start, ':professionals_finish' => $professionals_finish];
    $query->execute($params);
    return $query->fetchAll();
    }


    public function update(Professional $professional): bool
    {
        $sql = "
            UPDATE
                professionals
            SET
                id_user         = :id_user,
                id_professional_type = :id_professional_type
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_user'          => $professional->id_user,
            ':id_professional_type'  => $professional->id_professional_type,
            ':id'               => $professional->id
        ];
        return $query->execute($parameters);
    }
}
