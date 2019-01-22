<?php

use Phinx\Migration\AbstractMigration;

class ExportRemessa extends AbstractMigration
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
    public function up()
    {
        $permissions = [
        
                [
                    'resource' => '/admin/remessa/export',
                    'description' => 'Exportar Relatório de Remessas de Entrada.',
                    'role_id' => 2,
                ],
                [
                    'resource' => '/admin/remessa_saida/export',
                    'description' => 'Exportar Relatório de Remessas de Saída',
                    'role_id' => 2,
                ],
                [
                    'resource' => '/admin/remessa/export_history',
                    'description' => 'Exportar Histórico de Remessas de Entrada.',
                    'role_id' => 2,
                ],
                [
                    'resource' => '/admin/remessa_saida/export_history',
                    'description' => 'Exportar Histórico de Remessas de Saída',
                    'role_id' => 2,
                ]
            ];

            $this->insert('permissions', $permissions);
    }
}