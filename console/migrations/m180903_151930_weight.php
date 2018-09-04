<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180903_151930_weight
 */
class m180903_151930_weight extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'weight',Schema::TYPE_FLOAT);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180903_151930_weight cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180903_151930_weight cannot be reverted.\n";

        return false;
    }
    */
}
