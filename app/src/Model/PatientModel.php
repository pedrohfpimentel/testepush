<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Patient;

class PatientModel extends Model
{
    public function add(Patient $patient)
    {
        $sql = "
            INSERT INTO patients (
                id_user,
                id_patient_type,
                id_disease,
                tel_area_2,
                tel_numero_2,
                obs_tel,
                rg,
                sus,
                id_status,
                obs,
                cancer_type,
                discovery_time,
                discovery_how,
                treatment_time,
                treatment_where,
                doctor_name,
                fundation_need,
                visitDate,
                registration_date
                )
            VALUES (:id_user, :id_patient_type, :id_disease, :tel_area_2, :tel_numero_2, :obs_tel, :rg, :sus, :id_status, :obs, :cancer_type, :discovery_time, :discovery_how, :treatment_time, :treatment_where, :doctor_name, :fundation_need, :visitDate, :registration_date )
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id_user'          => $patient->id_user,
            ':id_patient_type'  => $patient->id_patient_type,
            ':id_disease'       => $patient->id_disease,
            ':tel_area_2'       => $patient->tel_area_2,
            ':tel_numero_2'     => $patient->tel_numero_2,
            ':obs_tel'          => $patient->obs_tel,
            ':rg'               => $patient->rg,
            ':sus'              => $patient->sus,
            ':id_status'        => $patient->id_status,
            ':obs'              => $patient->obs,
            ':cancer_type'      => $patient->cancer_type,
            ':discovery_time'   => $patient->discovery_time,
            ':discovery_how'    => $patient->discovery_how,
            ':treatment_time'   => $patient->treatment_time,
            ':treatment_where'  => $patient->treatment_where,
            ':doctor_name'      => $patient->doctor_name,
            ':fundation_need'   => $patient->fundation_need,
            ':visitDate'        => $patient->visitDate,
            ':registration_date'        => $patient->registration_date,


        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM patients WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        return $query->execute($parameters);
    }

    public function get(int $id)
    {
        $sql = "
            SELECT
                users.*,
                patients.id as patient_id,
                patients.tel_area_2 as tel_area_2,
                patients.tel_numero_2 as tel_numero_2,
                patients.obs_tel as obs_tel,
                patients.rg as rg,
                patients.sus as sus,
                patients.id_status as id_status,
                patients.obs as obs,
                patients.cancer_type as cancer_type,
                patients.discovery_time as discovery_time,
                patients.discovery_how as discovery_how,
                patients.treatment_time as treatment_time,
                patients.treatment_where as treatment_where,
                patients.doctor_name as doctor_name,
                patients.fundation_need as fundation_need,
                patients.visitDate as visitDate,
                patients.registration_date as registration_date,
                patients.id_user,
                diseases.id as disease_id,
                diseases.name as disease_name,
                diseases.description as disease_description,
                diseases.cid_version as disease_cid_version,
                diseases.cid_code as diseases_cid_code
            FROM
                patients LEFT JOIN users ON users.id = patients.id_user
            LEFT JOIN diseases ON patients.id_disease = diseases.id
            WHERE
                patients.id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
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
        return $query->fetch();
    }

    // retorna false se não encontrar o resultado
    public function getByCPF(string $cpf) {
        $sql = "
            SELECT
                *
            FROM
                users
            WHERE
                cpf = :cpf
            LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = [':cpf' => $cpf];
        $query->execute($parameters);
        return $query->fetch();
    }



    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                users.*,
                patients.*,
                users.id as id_user,
                users.name as user_name,
                diseases.id as disease_id,
                diseases.name as disease_name,
                diseases.description as disease_description,
                diseases.cid_version as disease_cid_version,
                diseases.cid_code as disease_cid_code
            FROM
                patients
                LEFT JOIN users ON users.id = patients.id_user
                LEFT JOIN diseases ON patients.id_disease = diseases.id
            ORDER BY
                users.name ASC
            LIMIT ? , ?
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
        return $query->fetchAll();



    }

    public function getAllByDate(string $start, string $finish, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
        SELECT
            users.*,
            patients.*,
            diseases.id as disease_id,
            diseases.name as disease_name,
            diseases.description as disease_description,
            diseases.cid_version as disease_cid_version,
            diseases.cid_code as disease_cid_code,
            patient_status.name as status_name
        FROM
            patients
            LEFT JOIN users ON users.id = patients.id_user
            LEFT JOIN diseases ON patients.id_disease = diseases.id
            LEFT JOIN patient_status ON patients.id_status = patient_status.id
        
        WHERE 
           patients.visitDate BETWEEN ? AND ?
        ORDER BY
            users.name ASC
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
    public function getAllByStatus( int $status = 1, string $start, string $finish, int $offset = 0, int $limit = PHP_INT_MAX): array

    {
        $sql = "
            SELECT
                users.*,
                patients.*,
                diseases.id as disease_id,
                diseases.name as disease_name,
                diseases.description as disease_description,
                diseases.cid_version as disease_cid_version,
                diseases.cid_code as disease_cid_code,
                patient_status.name as status_name

            FROM
                patients
                LEFT JOIN users ON users.id = patients.id_user
                LEFT JOIN diseases ON patients.id_disease = diseases.id
                LEFT JOIN patient_status ON patients.id_status = patient_status.id
            WHERE 
                patients.id_status =  ?
                AND (patients.visitDate BETWEEN ? AND ?)
            ORDER BY
                patients.id ASC
            LIMIT ? , ?

            
        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $status, \PDO::PARAM_INT);
        $query->bindValue(2, $start, \PDO::PARAM_STR);
        $query->bindValue(3, $finish, \PDO::PARAM_STR);
        $query->bindValue(4, $offset, \PDO::PARAM_INT);
        $query->bindValue(5, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Patient::class);
        return $query->fetchAll();



    }


     public function getAmount()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
                patients

        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    public function getAmountName($start, $finish, $search, $status)
    {
        $sql = "
            SELECT
                COUNT(id_user) AS amount,
                users.*,
                patients.*
                
            FROM
            patients
            LEFT JOIN users ON users.id = patients.id_user
                
            WHERE
                (patients.visitDate BETWEEN ? AND ?) 
        ";
        if($status != 0) {
            $sql .= "
                AND patients.id_status = ?
            ";
        }
        if($search != '') {
            $sql .= "
                AND users.name LIKE CONCAT('%',?, '%')
            ";
        }
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $start, \PDO::PARAM_STR);
        $query->bindValue(2, $finish, \PDO::PARAM_STR);
        if($status != 0){
            $query->bindValue(3, $status, \PDO::PARAM_INT);
            if($search != '') {
                $query->bindValue(4, $search, \PDO::PARAM_STR);
            }
            if($search == '') {
                
            }
        }
        if($status == 0){
            if($search != '') {
                $query->bindValue(3, $search, \PDO::PARAM_STR);
                
            }
            if($search == '') {
                
            }
        }
        $query->execute();
        return $query->fetch();
    }



    public function getPatientsByName($start, $finish, $search, $status, $order, int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT 
                users.*,
                patients.*,
                patient_status.name as status_name
            FROM
                patients
                LEFT JOIN users ON users.id = patients.id_user
                LEFT JOIN patient_status ON patients.id_status = patient_status.id
            WHERE
                (patients.visitDate BETWEEN ? AND ?) 
        ";
        if($status != 0) {
            $sql .= "
                AND patients.id_status = ?
            ";
        }
        if($search != '') {
            $sql .= "
                AND users.name LIKE CONCAT('%',?, '%')
            ";
        }
        if($order == 1) {
            $sql .= " ORDER BY patients.registration_date ASC";
        }
        if($order == 2) {
            $sql .= " ORDER BY users.name ASC";
        }
        if($order == 3) {
            $sql .= " ORDER BY patients.id_status ASC";
        }
        $sql .= "
            LIMIT ? , ?
        ";
        
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $start, \PDO::PARAM_STR);
        $query->bindValue(2, $finish, \PDO::PARAM_STR);
        if($status != 0){
            $query->bindValue(3, $status, \PDO::PARAM_INT);
            if($search != '') {
                $query->bindValue(4, $search, \PDO::PARAM_STR);
                $query->bindValue(5, $offset, \PDO::PARAM_INT);
                $query->bindValue(6, $limit, \PDO::PARAM_INT);
            }
            if($search == '') {
                $query->bindValue(4, $offset, \PDO::PARAM_INT);
                $query->bindValue(5, $limit, \PDO::PARAM_INT);
            }
        }
        if($status == 0){
            if($search != '') {
                $query->bindValue(3, $search, \PDO::PARAM_STR);
                $query->bindValue(4, $offset, \PDO::PARAM_INT);
                $query->bindValue(5, $limit, \PDO::PARAM_INT);
            }
            if($search == '') {
                $query->bindValue(3, $offset, \PDO::PARAM_INT);
                $query->bindValue(4, $limit, \PDO::PARAM_INT);
            }
        }
            
        $query->execute();
        return $query->fetchAll();
    }


     public function getPatientsDownload($patients_start, $patients_finish)
    {
        $sql = "
        SELECT 
            event_logs.date,
            patients.*
        FROM
            patients,
            event_logs

        WHERE
            event_logs.date BETWEEN :patients_start AND :patients_finish
    ";
    $query = $this->db->prepare($sql);

    $params = [':patients_start' => $patients_start, ':patients_finish' => $patients_finish];
    $query->execute($params);
    return $query->fetchAll();
    }

    public function update(Patient $patient): bool
    {
        $sql = "
            UPDATE
                patients
            SET
                id_user         = :id_user,
                id_patient_type = :id_patient_type,
                id_disease      = :id_disease,
                tel_area_2      = :tel_area_2,
                tel_numero_2    = :tel_numero_2,
                obs_tel         = :obs_tel,
                rg              = :rg,
                sus             = :sus,
                id_status       = :id_status,
                obs             = :obs,
                cancer_type     = :cancer_type,
                discovery_time  = :discovery_time,
                discovery_how   = :discovery_how,
                treatment_time  = :treatment_time,
                treatment_where = :treatment_where,
                doctor_name     = :doctor_name,
                fundation_need  = :fundation_need,
                visitDate       = :visitDate,
                registration_date = :registration_date
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':id'               => $patient->id,
            ':id_user'          => $patient->id_user,
            ':id_patient_type'  => $patient->id_patient_type,
            ':id_disease'       => $patient->id_disease,
            ':tel_area_2'       => $patient->tel_area_2,
            ':tel_numero_2'     => $patient->tel_numero_2,
            ':obs_tel'          => $patient->obs_tel,
            ':rg'               => $patient->rg,
            ':sus'              => $patient->sus,
            ':id_status'        => $patient->id_status,
            ':obs'              => $patient->obs,
            ':cancer_type'      => $patient->cancer_type,
            ':discovery_time'   => $patient->discovery_time,
            ':discovery_how'    => $patient->discovery_how,
            ':treatment_time'   => $patient->treatment_time,
            ':treatment_where'  => $patient->treatment_where,
            ':doctor_name'      => $patient->doctor_name,
            ':fundation_need'   => $patient->fundation_need,
            ':visitDate'        => $patient->visitDate,
            ':registration_date'        => $patient->registration_date,

        ];

        return $query->execute($parameters);
    }
}
