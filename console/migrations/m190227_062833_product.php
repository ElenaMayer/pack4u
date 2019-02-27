<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190227_062833_product
 */
class m190227_062833_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'instruction',Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190227_062833_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190227_062833_product cannot be reverted.\n";

        return false;
    }
    */
}
