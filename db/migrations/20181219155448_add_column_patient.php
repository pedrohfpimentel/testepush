<?php

use Phinx\Migration\AbstractMigration;

class AddColumnPatient extends AbstractMigration
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
        $patients = $this->table('patients');
        
        $patients->addColumn('cancer_type', 'string', ['null' => true]);;
        $patients->addColumn('discovery_time', 'string', ['null' => true]);
        $patients->addColumn('discovery_how', 'string', ['null' => true]);
        $patients->addColumn('treatment_time', 'string', ['null' => true]);
        $patients->addColumn('treatment_where', 'string', ['null' => true]);
        $patients->addColumn('doctor_name', 'string', ['null' => true]);
        $patients->addColumn('fundation_need', 'string', ['null' => true]);
        $patients->update();
    }
}
