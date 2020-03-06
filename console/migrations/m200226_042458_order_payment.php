<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m200226_042458_order_payment
 */
class m200226_042458_order_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'payment_id',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'payment_url',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'payment_error', Schema::TYPE_STRING);
        $this->alterColumn('{{%order}}', 'payment', Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'shipping_number', Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'is_ul', Schema::TYPE_BOOLEAN);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200226_042458_order_payment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200226_042458_order_payment cannot be reverted.\n";

        return false;
    }
    */
}
