<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m211010_121345_history
 */
class m211010_121345_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_history', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER,
            'diversity_id' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            'order_id' => Schema::TYPE_INTEGER,
            'order_item_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING,
            'count_old' => Schema::TYPE_INTEGER,
            'count_new' => Schema::TYPE_INTEGER,
            'created_at' => 'TIMESTAMP DEFAULT NOW()',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211010_121345_history cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211010_121345_history cannot be reverted.\n";

        return false;
    }
    */
}
