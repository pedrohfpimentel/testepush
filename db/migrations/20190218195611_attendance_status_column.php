<?php

use Phinx\Migration\AbstractMigration;

class AttendanceStatusColumn extends AbstractMigration
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
        $attendances = $this->table('attendances');
        $attendances->addColumn('status', 'integer');
        $attendances->update();
    


   
        $event_log_types = [
            [
                'id' => 17,
                'slug' => 'attendance_edit',
                'name' => 'Edição de Atendimento',
                'description' => 'Evento de Edição'
            ]
        ];
        $this->insert('event_log_types', $event_log_types);
    }
}
