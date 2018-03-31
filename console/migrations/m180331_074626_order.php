<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180331_074626_order
 */
class m180331_074626_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'tk',Schema::TYPE_STRING);
        $this->addColumn('{{%order}}', 'rcr',Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180331_074626_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180331_074626_order cannot be reverted.\n";

        return false;
    }
    */
}
