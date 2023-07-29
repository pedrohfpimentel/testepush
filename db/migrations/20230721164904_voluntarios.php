<?php

use Phinx\Migration\AbstractMigration;

class Voluntarios extends AbstractMigration
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
        
        $patients->addColumn('registration_date', 'date', ['null' => true]);
        $patients->update();


        $volunteers = $this->table('volunteers');
        $volunteers->addColumn('name', 'string');
        $volunteers->addColumn('email', 'string', ['null' => true]);
        $volunteers->addColumn('nascimento', 'date', ['null' => true]);
        $volunteers->addColumn('cpf', 'string', ['null' => true]);
        $volunteers->addColumn('tel_area', 'integer', ['null' => true]);
        $volunteers->addColumn('tel_numero', 'integer', ['null' => true]);
        $volunteers->addColumn('end_rua', 'string', ['null' => true]);
        $volunteers->addColumn('end_numero', 'integer', ['null' => true]);
        $volunteers->addColumn('end_complemento', 'string', ['null' => true]);
        $volunteers->addColumn('end_bairro', 'string', ['null' => true]);
        $volunteers->addColumn('end_cidade', 'string', ['null' => true]);
        $volunteers->addColumn('end_estado', 'string', ['null' => true]);
        $volunteers->addColumn('end_cep', 'string', ['null' => true]);
        $volunteers->addColumn('obs', 'text', ['null' => true]);
        $volunteers->addColumn('status', 'integer');
        $volunteers->create();

        $permissions = [

            [
                'resource' => '/admin/voluntarios',
                'description' => 'Administração de voluntarios.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/add',
                'description' => 'Administração de voluntarios -Add.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/edit/:id',
                'description' => 'Administração de voluntarios - Edit.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/history/:id',
                'description' => 'Histórico de Eventos do Paciente.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/export/',
                'description' => 'Exportar Informações do Paciente.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/remove/:id',
                'description' => 'Administração de voluntarios - Remove.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/update',
                'description' => 'Administração de voluntarios - update.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/voluntarios/verifyUserByEmail',
                'description' => 'Verificar se o usuário existe pelo email.',
                'role_id' => 2,
            ],
            [
                'resource' => '/admin/patients/export_xlsx',
                'description' => 'Exportar Informações do Paciente.',
                'role_id' => 2,
            ],
        ];
        $this->insert('permissions', $permissions);

        $event_log_types = [
            
            [
                'id' => 22,
                'slug' => 'create_volunteer',
                'name' => 'Adição de Voluntário',
                'description' => 'Adição de voluntário.'
            ],
            [
                'id' => 23,
                'slug' => 'edit_volunteer',
                'name' => 'Edição de Voluntário',
                'description' => 'Edição de voluntário.'
            ],

        ];
        $this->insert('event_log_types', $event_log_types);
    }
}
