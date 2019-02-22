<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\AttendanceStatusModel;

class AttendanceStatusModel extends Model
{
    public function add(AttendanceStatus $attendance_status)
    {
        $sql = "
            INSERT INTO attendance_status (
                name
                )
            VALUES (:name)
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':name'          => $attendance_status->name,


        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM attendance_status WHERE id = :id";
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
                attendance_status
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        //$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, AttendanceStatus::class);
        return $query->fetch();
    }

    public function getAll(): array
    {
        $sql = "
            SELECT
               *
            FROM
                attendance_status
            ORDER BY
                id ASC
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, AttendanceStatus::class);
        return $query->fetchAll();
    }

    public function update(AttendanceStatus $attendance_status): bool
    {
        $sql = "
            UPDATE
                attendance_status
            SET
                name         = :name
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':name'          => $attendance->name
        ];
        return $query->execute($parameters);
    }
}
