<?php

use Phinx\Migration\AbstractMigration;

class RemessaTypeData extends AbstractMigration
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
               'id' => 1,
               'slug' => 'entrada_doacao',
               'name' => 'Recebimento de Doação',
               'description' => 'Recebimento de doação de itens diversos.'
           ],
           [
               'id' => 2,
               'slug' => 'entrada_compra',
               'name' => 'Compra de item',
               'description' => 'Compra de itens diversos.'
           ],
           [
               'id' => 3,
               'slug' => 'remessa_inicial',
               'name' => 'Remessa Inicial',
               'description' => 'Entrada inicial de produtos.'
           ],
           [
               'id' => 4,
               'slug' => 'saida_doacao',
               'name' => 'Saída Doação',
               'description' => 'Saída de doação de itens diversos.'
           ],
           [
               'id' => 5,
               'slug' => 'saida_emprestimo',
               'name' => 'Saída por empréstimo',
               'description' => 'Saída de itens diversos por empréstimo.'
           ],
           [
               'id' => 99,
               'slug' => 'remessa_type_temp',
               'name' => 'Remessa Temporária',
               'description' => 'Entrada temporaria de produtos.'
           ]    
         ];
         $this->insert('remessa_type', $remessaType);
     }
}
