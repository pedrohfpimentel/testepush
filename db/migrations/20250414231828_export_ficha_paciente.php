<?php

use Phinx\Migration\AbstractMigration;

class ExportFichaPaciente extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $permissions = [
            [
                'resource' => '/admin/patients/export/:id',
                'description' => 'Exportar Informações da ficha do Paciente.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/patients/docs/:id',
                'description' => 'Lista de arquivos do Paciente.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/patients/docs/:id/add',
                'description' => 'Adicionar arquivo Paciente.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/patients/docs/:id/remove/:id_doc',
                'description' => 'Remover arquivo do Paciente.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/patients/docs/:id/export/:id_doc',
                'description' => 'Visualizar arquivo do Paciente.',
                'role_id' => 2,
            ],
        ];
        $this->insert('permissions', $permissions);

        $patient_file = $this->table('patient_file');
        $patient_file->addColumn('id_patient', 'integer');
        $patient_file->addColumn('name', 'string');
        $patient_file->addColumn('url_file', 'string');
        $patient_file->create();
    }
}
