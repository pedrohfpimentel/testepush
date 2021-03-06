<?php

use Phinx\Migration\AbstractMigration;

class ProductsData extends AbstractMigration
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
        $products = [
            [
                'id'          => 1,
                'name'        => 'Produto Padrão',
                'description' => 'Descrição do Produto Padrão',
                'category'    => 1,
                'id_supplier' => 1,
                'patrimony'   => 1,
               // 'patrimony_code' => 12345,
            ]
        ];
        $this->insert('products', $products);
    }

}
