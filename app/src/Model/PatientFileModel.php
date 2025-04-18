<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\PatientFile;

class PatientFileModel extends Model
{
    public function add(PatientFile $patient_file)
    {
        $sql = "
            INSERT INTO patient_file (
                id_patient,
                name,
                url_file
                )
            VALUES (:id_patient, :name, :url_file )
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_patient'          => $patient_file->id_patient,
            ':name'  => $patient_file->name,
            ':url_file'       => $patient_file->url_file,


        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM patient_file WHERE id = :id";
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
                patient_file 
            WHERE
                patient_file.id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
        return $query->fetch();
    }


    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                *
            FROM
                patient_file
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
        return $query->fetchAll();



    }

    public function getAllByPatient($patient_id): array
    {
        $sql = "
            SELECT
                *
            FROM
                patient_file
            WHERE
                id_patient = ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $patient_id, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
        return $query->fetchAll();



    }

}
