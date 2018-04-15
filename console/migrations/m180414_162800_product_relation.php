<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180414_162800_product_relation
 */
class m180414_162800_product_relation extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%product_relation}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER,
            'child_id' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->addForeignKey('fk-product_relation-parent_id-product-id', '{{%product_relation}}', 'parent_id', '{{%product}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-product_relation-child_id-product-id', '{{%product_relation}}', 'child_id', '{{%product}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180414_162800_product_relation cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180414_162800_product_relation cannot be reverted.\n";

        return false;
    }
    */
}
