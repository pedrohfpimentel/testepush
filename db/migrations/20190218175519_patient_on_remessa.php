<?php

use Phinx\Migration\AbstractMigration;

class PatientOnRemessa extends AbstractMigration
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
        
        $remessa->addColumn('patient_id', 'integer', ['null' => true]);
        $remessa->update();


        $remessaType = [
            [
                'id'    => '6',
                'slug' => 'entrada_devolucao',
                'name' => 'Devolução de produtos',
                'description' => 'Recebimento de devolução de itens diversos.'
            ]   
          ];
          $this->insert('remessa_type', $remessaType);
    }
}
