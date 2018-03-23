<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180323_153534_order
 */
class m180323_153534_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'fio',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'shipping_cost',Schema::TYPE_INTEGER);
        $this->addColumn('{{%order}}', 'city',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'shipping_method',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'payment_method',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'zip',Schema::TYPE_INTEGER);

        $this->addColumn('{{%category}}', 'description',Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180323_153534_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180323_153534_order cannot be reverted.\n";

        return false;
    }
    */
}
