<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

use Farol360\Ancora\Model;
use Farol360\Ancora\Model\Volunteer;

class VolunteerModel extends Model
{
    public function add(Volunteer $volunteer)
    {
        $sql = "
            INSERT INTO volunteers (
                email,
                name,
                nascimento,
                cpf,
                tel_area,
                tel_numero,
                end_cep,
                end_rua,
                end_numero,
                end_complemento,
                end_bairro,
                end_cidade,
                end_estado,
                obs,
                status
                )
            VALUES (
                :email, 
                :name, 
                :nascimento, 
                :cpf, 
                :tel_area, 
                :tel_numero, 
                :end_cep, 
                :end_rua, 
                :end_numero, 
                :end_complemento,
                :end_bairro,
                :end_cidade,
                :end_estado,
                :obs,
                :status
            )
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':email'          => $volunteer->email,
            ':name'          => $volunteer->name,
            ':nascimento'          => $volunteer->nascimento,
            ':cpf'          => $volunteer->cpf,
            ':tel_area'          => $volunteer->tel_area,
            ':tel_numero'          => $volunteer->tel_numero,
            ':end_cep'          => $volunteer->end_cep,
            ':end_rua'          => $volunteer->end_rua,
            ':end_numero'          => $volunteer->end_numero,
            ':end_complemento'          => $volunteer->end_complemento,
            ':end_bairro'          => $volunteer->end_bairro,
            ':end_cidade'          => $volunteer->end_cidade,
            ':end_estado'          => $volunteer->end_estado,
            ':obs'          => $volunteer->obs,
            ':status' => $volunteer->status
        ];
        if ($query->execute($parameters)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }

    public function delete(int $id): bool
    {
       $sql = "DELETE FROM volunteers WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        return $query->execute($parameters);
    }

    public function get(int $id)
    {
        $sql = "
            SELECT
                volunteers.*
            FROM
                volunteers
            WHERE
                volunteers.id = :id
            LIMIT 1
        ";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Volunteer::class);
        return $query->fetch();
    }

    public function getByEmail(string $email) {
        
        $sql = "
            SELECT
                *
            FROM
                volunteers
            WHERE
                email = :email
            LIMIT 1";
        $query = $this->db->prepare($sql);
        $parameters = [':email' => $email];
        $query->execute($parameters);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Volunteers::class);
        return $query->fetch();
    }

    public function getAll(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                volunteers.*

            FROM
                volunteers
            ORDER BY
                volunteers.name ASC
                LIMIT ? , ?

        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Volunteer::class);
        return $query->fetchAll();
    }


    public function getAllIndex(int $offset = 0, int $limit = PHP_INT_MAX): array
    {
        $sql = "
            SELECT
                volunteers.*

            FROM
                volunteers

            WHERE
                volunteers.status IS NULL OR volunteers.status = 1

            ORDER BY
                volunteers.name ASC
                LIMIT ? , ?

        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $offset, \PDO::PARAM_INT);
        $query->bindValue(2, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Volunteers::class);
        return $query->fetchAll();
    }


       public function getAmount()
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
            volunteers

        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch();
    }




    public function getAmountStatus( $status = 0)
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
                volunteers
                WHERE
                volunteers.status =  ?

        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $status, \PDO::PARAM_STR);
        $query->execute();
        return $query->fetch();
    }




    public function getAmountStatus1( $status = 0)
    {
        $sql = "
            SELECT
                COUNT(id) AS amount
            FROM
            volunteers
                WHERE
                volunteers.status IS NULL OR volunteers.status = 1

        ";
        $query = $this->db->prepare($sql);
        //$query->bindValue(1, $status, \PDO::PARAM_STR);
        $query->execute();
        return $query->fetch();
    }

    public function getAllByStatus( int $status = 0, int $offset = 0, int $limit = PHP_INT_MAX): array

    {
        $sql = "
            SELECT
                volunteers.*

            FROM
                volunteers
            WHERE
                volunteers.status =  ?

            ORDER BY
            volunteers.id ASC
            LIMIT ? , ?


        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $status, \PDO::PARAM_INT);
        $query->bindValue(2, $offset, \PDO::PARAM_INT);
        $query->bindValue(3, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Volunteer::class);
        return $query->fetchAll();
    }



    public function getAllByStatus2( int $status = 0, int $offset = 0, int $limit = PHP_INT_MAX): array

    {
        $sql = "
            SELECT
                volunteers.*

            FROM
            volunteers
            WHERE
            volunteers.status IS NULL OR volunteers.status = ?

            ORDER BY
            volunteers.id ASC
            LIMIT ? , ?


        ";
        $query = $this->db->prepare($sql);
        $query->bindValue(1, $status, \PDO::PARAM_INT);
        $query->bindValue(2, $offset, \PDO::PARAM_INT);
        $query->bindValue(3, $limit, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Volunteer::class);
        return $query->fetchAll();

    }



    public function update(Volunteer $volunteer): bool
    {
        $sql = "
            UPDATE
                volunteers
            SET
                name = :name,
                email = :email,
                nascimento = :nascimento,
                cpf = :cpf,
                tel_area = :tel_area,
                tel_numero = :tel_numero,
                end_cep = :end_cep,
                end_rua = :end_rua,
                end_numero = :end_numero,
                end_complemento = :end_complemento,
                end_bairro = :end_bairro,
                end_cidade = :end_cidade,
                end_estado = :end_estado,
                obs = :obs,
                status = :status
            WHERE
                id = :id
        ";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':name' => $volunteer->name,
            ':email' => $volunteer->email,
            ':nascimento' => $volunteer->nascimento,
            ':cpf' => $volunteer->cpf,
            ':tel_area' => $volunteer->tel_area,
            ':tel_numero' => $volunteer->tel_numero,
            ':end_cep' => $volunteer->end_cep,
            ':end_rua' => $volunteer->end_rua,
            ':end_numero' => $volunteer->end_numero,
            ':end_complemento' => $volunteer->end_complemento,
            ':end_bairro' => $volunteer->end_bairro,
            ':end_cidade' => $volunteer->end_cidade,
            ':end_estado' => $volunteer->end_estado,
            ':obs' => $volunteer->obs,
            ':status' => $volunteer->status,
            ':id'               => $volunteer->id
        ];
        return $query->execute($parameters);
    }
}
