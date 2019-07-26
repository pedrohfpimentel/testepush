<?php

use Phinx\Migration\AbstractMigration;

class CorrecaoDeEstoqueMigration extends AbstractMigration
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
        $remessaType = [
            [
                'id' => 7,
                'slug' => 'entrada_correcao',
                'name' => 'Correção de Estoque - Entrada',
                'description' => 'Entrada para correção de estoque.'
            ], [
                'id' => 8,
                'slug' => 'saida_correcao',
                'name' => 'Correção de Estoque - Saída',
                'description' => 'Saída para correção de estoque.'
            ]
          ];
          $this->insert('remessa_type', $remessaType);

          $event_log_types = [
            [
                'id' => 20,
                'slug' => 'entrada_correcao',
                'name' => 'Correção de Estoque - Entrada',
                'description' => 'Entrada para correção de estoque.'
            ],

            [
                'id' => 21,
                'slug' => 'saida_correcao',
                'name' => 'Correção de Estoque - Saída',
                'description' => 'Saída para correção de estoque.'
            ],

        ];
        $this->insert('event_log_types', $event_log_types);
    }
}
