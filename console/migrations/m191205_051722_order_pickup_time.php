<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m191205_051722_order_pickup_time
 */
class m191205_051722_order_pickup_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'pickup_time',Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191205_051722_order_pickup_time cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191205_051722_order_pickup_time cannot be reverted.\n";

        return false;
    }
    */
}
