<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180317_071610_product
 */
class m180317_071610_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'article',Schema::TYPE_STRING);
        $this->addColumn('{{%product}}', 'is_in_stock',Schema::TYPE_BOOLEAN);
        $this->addColumn('{{%product}}', 'is_active',Schema::TYPE_BOOLEAN);
        $this->addColumn('{{%product}}', 'is_novelty',Schema::TYPE_BOOLEAN);
        $this->addColumn('{{%product}}', 'size',Schema::TYPE_STRING);
        $this->addColumn('{{%product}}', 'color',Schema::TYPE_STRING);
        $this->addColumn('{{%product}}', 'tags',Schema::TYPE_STRING);
        $this->addColumn('{{%product}}', 'new_price', Schema::TYPE_INTEGER);
        $this->addColumn('{{%product}}', 'time',Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()');

        $this->addColumn('{{%category}}', 'is_active',Schema::TYPE_BOOLEAN);
        $this->addColumn('{{%category}}', 'time',Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180317_071610_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180317_071610_product cannot be reverted.\n";

        return false;
    }
    */
}
