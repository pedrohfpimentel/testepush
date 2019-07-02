<?php

use Phinx\Migration\AbstractMigration;

class RemessaEventLogData extends AbstractMigration
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
    public function up() {
        $event_log_types = [
            [
                'id' => 18,
                'slug' => 'remessa_edit',
                'name' => 'Edição na entrada de estoque',
                'description' => 'Edição na entrada de estoque.'
            ],

            [
                'id' => 19,
                'slug' => 'remessa_saida_edit',
                'name' => 'Edição na saída de estoque',
                'description' => 'Edição na saída de estoque.'
            ],

        ];
        $this->insert('event_log_types', $event_log_types);

    }
}
