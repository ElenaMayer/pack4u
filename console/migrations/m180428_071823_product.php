<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180428_071823_product
 */
class m180428_071823_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->addColumn('{{%product}}', 'count',Schema::TYPE_INTEGER);

        $this->update( '{{%product}}', ['count' => 0]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180428_071823_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180428_071823_product cannot be reverted.\n";

        return false;
    }
    */
}
