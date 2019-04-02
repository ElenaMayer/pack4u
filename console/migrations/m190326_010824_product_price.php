<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m190326_010824_product_price
 */
class m190326_010824_product_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        $this->createTable('{{%product_price}}', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER,
            'price' => Schema::TYPE_INTEGER,
            'count' => Schema::TYPE_INTEGER,
        ], $tableOptions);
        $this->addForeignKey('fk-product_price-product_id-product_id', '{{%product_price}}', 'product_id', 'product', 'id', 'SET NULL');

        $this->addColumn('{{%product}}', 'multiprice',Schema::TYPE_BOOLEAN);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190326_010824_product_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190326_010824_product_price cannot be reverted.\n";

        return false;
    }
    */
}
