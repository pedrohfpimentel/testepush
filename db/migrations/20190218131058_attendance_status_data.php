<?php

use Phinx\Migration\AbstractMigration;

class AttendanceStatusData extends AbstractMigration
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
        $attendance_status = [
            [
                'id' => 1,
                'name' => 'Pré Agendado',

            ],
            [
                'id' => 2,
                'name' => 'Realizado',

            ],
            [
                'id' => 3,
                'name' => 'Cancelado',

            ],
            [
                'id' => 4,
                'name' => 'Removido',

            ],
        ];

        $this->insert('attendance_status', $attendance_status);
    }
}
