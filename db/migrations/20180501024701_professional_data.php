<?php

use Phinx\Migration\AbstractMigration;

class ProfessionalData extends AbstractMigration
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
        $professional_type = [
            [
                'id' => 1,
                'name' => 'Tipo Padrão',
                'description' => 'Tipo padrão de Profissional',
                'register' => 'CRM-123'
            ]
        ];
        $this->insert('professional_types', $professional_type);
    }

}
