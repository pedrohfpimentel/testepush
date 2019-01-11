<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Attendance;

class AttendanceModel extends Model
{
    public function add(Attendance $attendance)
    {
        $sql = "
            INSERT INTO attendances (
                id_patient,
                id_professional,
                attendance_day,
                attendance_hour,
                description
                )
            VALUES (
                :id_patient,
                :id_professional,
                :attendance_day,
                :attendance_hour,
                :description
                )
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_patient'           => $attendance->id_patient,
            ':id_professional'      => $attendance->id_professional,
            ':attendance_day'                 => $attendance->attendance_day,
            ':attendance_hour'                 => $attendance->attendance_hour,
            ':description'          => $attendance->description
        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM attendances WHERE id = :id";
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
                attendances
            WHERE
                id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Attendance::class);
        return $query->fetch();
    }

    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                attendances
            ORDER BY
                attendance_day
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Attendance::class);
        return $query->fetchAll();
    }

    public function getByPatient(int $id)
    {
        $sql = "
            SELECT
                event_logs.id as id,
                event_logs.id_event_log_type,
                event_logs.id_patient,
                event_logs.id_professional,
                event_logs.attendance_day,
                event_logs.description,
                event_logs.attendance_start,
                event_log_types.id as event_log_types_id,
                event_log_types.slug as event_log_types_slug,
                event_log_types.name as event_log_types_name,
                event_log_types.description as event_log_types_description,
                event_log_types.attendance_start as event_log_types_attendance_start,
                patients.id as patients_id,
                patients.id_user as patients_id_user,
                users.name as users_name,
                users.email as users_email

            FROM
                event_logs
                LEFT JOIN event_log_types ON event_logs.id_event_log_type = event_log_types.id
                LEFT JOIN patients ON patients.id = event_logs.id_patient
                LEFT JOIN users ON patients.id_user = users.id
            WHERE
                id_patient = :id

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
                event_logs.id_event_log_type,
                event_logs.id_patient,
                event_logs.id_professional,
                event_logs.attendance_day,
                event_logs.description,
                event_logs.attendance_start,
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
                LEFT JOIN event_log_types ON event_logs.id_event_log_type = event_log_types.id
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


    public function getAmount()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
               
            FROM
                attendances
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    

    public function getAttendancesDownload($attendance_start, $attendance_finish)
    {
        $sql = "
        SELECT 
            attendances.*,
            (SELECT users.name 
            FROM users 
            LEFT JOIN 
                patients ON patients.id_user = users.id
            WHERE users.id = patients.id_user) AS patient_name,
            (SELECT users.name 
            FROM users 
            LEFT JOIN 
                professionals ON professionals.id_user = users.id
            WHERE users.id = professionals.id_user) AS professional_name
        FROM `attendances`

        WHERE
            attendances.attendance_day BETWEEN :attendance_start AND :attendance_finish
    ";
    $query = $this->db->prepare($sql);

    $params = [':attendance_start' => $attendance_start, ':attendance_finish' => $attendance_finish];
    $query->execute($params);
    return $query->fetchAll();
    }



    public function getAllByDate(string $start, string $finish, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
        SELECT 
            attendances.*,
            (SELECT users.name 
            FROM users 
            LEFT JOIN 
                patients ON patients.id_user = users.id
            WHERE users.id = patients.id_user) AS patient_name,
            (SELECT users.name 
            FROM users 
            LEFT JOIN 
                professionals ON professionals.id_user = users.id
            WHERE users.id = professionals.id_user) AS professional_name
        FROM `attendances`
        
        WHERE 
           attendances.attendance_day BETWEEN ? AND ?
        ORDER BY
            attendances.attendance_day ASC
        LIMIT ? , ?

        
    ";
    $query = $this->db->prepare($sql);
    $query->bindValue(1, $start, \PDO::PARAM_STR);
    $query->bindValue(2, $finish, \PDO::PARAM_STR);
    $query->bindValue(3, $offset, \PDO::PARAM_INT);
    $query->bindValue(4, $limit, \PDO::PARAM_INT);
    $query->execute();
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
    return $query->fetchAll();



    }


    public function update(Attendance $attendances): bool
    {
        $sql = "
            UPDATE
                attendances
            SET
                id_patient          = :id_patient,
                id_professional     = :id_professional,
                attendance_day      = :attendance_day,
                attendance_hour     = :attendance_hour,
                description         = :description,
                attendance_start    = :attendance_start
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_patient'       => $eventLog->id_patient,
            ':id_professional'  => $eventLog->id_professional,
            ':attendance_day'   => $eventLog->attendance_day,
            ':attendance_hour'  => $eventLog->attendance_hour,
            ':description'      => $eventLog->description,
            ':attendance_start' => $eventLog->attendance_start,
            ':id'               => $eventLog->id
        ];
        return $query->execute($parameters);
    }
}
