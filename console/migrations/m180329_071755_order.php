<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180329_071755_order
 */
class m180329_071755_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'user_id',Schema::TYPE_INTEGER);

        $this->addColumn('{{%user}}', 'fio',Schema::TYPE_STRING);
        $this->addColumn('{{%user}}', 'address',Schema::TYPE_STRING);
        $this->addColumn('{{%user}}', 'phone',Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180329_071755_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180329_071755_order cannot be reverted.\n";

        return false;
    }
    */
}
