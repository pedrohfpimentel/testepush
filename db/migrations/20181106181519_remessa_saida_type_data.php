<?php

use Phinx\Migration\AbstractMigration;

class RemessaSaidaTypeData extends AbstractMigration
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
         $remessaType = [
           [
               'id' => 4,
               'slug' => 'saida_doacao',
               'name' => 'Saida de Doação',
               'description' => 'Recebimento de doação de itens diversos.'
           ],
           [
               'id' => 5,
               'slug' => 'saida_emprestimo',
               'name' => 'Saida de Empréstimo',
               'description' => 'Saída do item por empréstimo.'
           ],
         ];
         $this->insert('remessa_type', $remessaType);
     }
}
