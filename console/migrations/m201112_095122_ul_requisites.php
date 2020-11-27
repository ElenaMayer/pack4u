<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m201112_095122_ul_requisites
 */
class m201112_095122_ul_requisites extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        $this->addColumn('{{%order}}', 'ul_requisites',Schema::TYPE_STRING);
        $this->addColumn('{{%category}}', 'sort',Schema::TYPE_INTEGER);

        $this->createTable('{{%product_notification}}', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER,
            'diversity_id' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING,
            'phone' => Schema::TYPE_STRING,
            'is_active' => Schema::TYPE_BOOLEAN,
            'comment' => Schema::TYPE_TEXT,
            'created_at' => 'TIMESTAMP DEFAULT NOW()',
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201112_095122_ul_requisites cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201112_095122_ul_requisites cannot be reverted.\n";

        return false;
    }
    */
}
