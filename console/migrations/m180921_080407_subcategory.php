<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180921_080407_subcategory
 */
class m180921_080407_subcategory extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->addColumn('{{%product}}', 'subcategories',Schema::TYPE_STRING);
        $this->addColumn('{{%product}}', 'sort',Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180921_080407_subcategory cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180921_080407_subcategory cannot be reverted.\n";

        return false;
    }
    */
}
