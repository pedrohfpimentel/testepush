<?php

use Phinx\Migration\AbstractMigration;

class ExportAttendancePatient extends AbstractMigration
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
                    'resource' => '/admin/patients/export_history_attendance',
                    'description' => 'Exportar Histórico de Atendimento Paciente.',
                    'role_id' => 2,
                ],
                [
                    'resource' => '/admin/products/export_history_remessa',
                    'description' => 'Exportar Histórico de Remessa.',
                    'role_id' => 2,
                ],
                [
                    'resource' => '/admin/attendances/export_data',
                    'description' => 'Exportar Histórico de Atendimento por data do Atendimento.',
                    'role_id' => 2,
                ]

            ];

            $this->insert('permissions', $permissions);
    }
}
