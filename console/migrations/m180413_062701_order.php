<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180413_062701_order
 */
class m180413_062701_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->addColumn('{{%order}}', 'payment',Schema::TYPE_BOOLEAN);
        $this->update( '{{%order}}', ['status' => 'new']);
        $this->update( '{{%order}}', ['payment' => 0]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180413_062701_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_062701_order cannot be reverted.\n";

        return false;
    }
    */
}
