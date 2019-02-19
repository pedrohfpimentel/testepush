<?php

use Phinx\Migration\AbstractMigration;

class AddColumnRemessaRoutes extends AbstractMigration
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
        $remessa = $this->table('remessa');
        
        $remessa->addColumn('suppliers', 'string', ['null' => true]);
        $remessa->update();


         $permissions = [
            //adicionar rotas para produto_remessa 
            [
                'resource' => '/admin/produto_remessa',
                'description' => 'Administração de Remessa e Produto.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/produto_remessa/add',
                'description' => 'Administração de Remessa e Produto -Add.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/produto_remessa/edit/:id',
                'description' => 'Administração de Remessa e Produto - Edit.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/produto_remessa/history/:id',
                'description' => 'Histórico de Eventos do Remessa e Produto.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/produto_remessa/export/',
                'description' => 'Exportar Informações do Remessa e Produto.',
                'role_id' => 2,
            ],

            [
                'resource' => '/admin/produto_remessa/export_history/',
                'description' => 'Exportar Informações do Histórico de Remessa e Produto.',
                'role_id' => 2,
            ],

            [
                'resource' => '/admin/produto_remessa/remove/:id',
                'description' => 'Administração de Remessa e Produto - Remove.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/produto_remessa/update',
                'description' => 'Administração de Pacientes - update.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/remessa/add_ajax',
                'description' => 'Administração de Remessa',
                'role_id' => 2,
            ]
            ];

            $this->insert('permissions', $permissions);
    }
}
