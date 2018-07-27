<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180727_112226_sale
 */
class m180727_112226_sale extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'discount',Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180727_112226_sale cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180727_112226_sale cannot be reverted.\n";

        return false;
    }
    */
}
