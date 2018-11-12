<?php

use Phinx\Migration\AbstractMigration;

class Remessa extends AbstractMigration
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
        $remessa = $this->table('remessa');
        $remessa->addColumn('id_product', 'integer', ['null' => true]);
        $remessa->addColumn('remessa_type', 'integer');
        $remessa->addColumn('quantity', 'integer', ['null' => true]);
        $remessa->addColumn('cost', 'string', ['null' => true]);
        $remessa->addColumn('date', 'timestamp', ['null' => true]);
        $remessa->addColumn('time', 'time', ['null' => true]);
        $remessa->addColumn('patrimony_code', 'integer',['null' => true]);
        $remessa->addTimestamps();
        $remessa->create();
    }
}
