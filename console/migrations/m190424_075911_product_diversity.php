<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m190424_075911_product_diversity
 */
class m190424_075911_product_diversity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        $this->addColumn('{{%product}}', 'diversity',Schema::TYPE_BOOLEAN);

        $this->createTable('{{%product_diversity}}', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER,
            'image_id' => Schema::TYPE_INTEGER,
            'article' => Schema::TYPE_STRING,
            'title' => Schema::TYPE_STRING,
            'is_in_stock' => Schema::TYPE_BOOLEAN,
            'is_active' => Schema::TYPE_BOOLEAN,
            'count' => Schema::TYPE_INTEGER,
            'color' => Schema::TYPE_STRING,
        ], $tableOptions);

        $this->addForeignKey('fk-product_diversity-product_id-product_id', '{{%product_diversity}}', 'product_id', 'product', 'id', 'SET NULL');

        $this->execute('INSERT INTO `product_diversity`(`product_id`, `image`, `article`, `is_in_stock`, `is_active`, `count`, `color`) 
                            SELECT
                            `id`, (SELECT id from image where product_id = product.id limit 1), `article`, `is_in_stock`, `is_active`, `count`, `color`
                            FROM
                            product');

        $this->addColumn('{{%order_item}}', 'diversity_id',Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190424_075911_product_diversity cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190424_075911_product_diversity cannot be reverted.\n";

        return false;
    }
    */
}
