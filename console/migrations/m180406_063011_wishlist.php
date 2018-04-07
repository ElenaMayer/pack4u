<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m180406_063011_wishlist
 */
class m180406_063011_wishlist extends Migration
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
        $this->createTable('{{%wishlist}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'product_id' => Schema::TYPE_INTEGER,
        ], $tableOptions);
        $this->createTable('{{%cart_item}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'product_id' => Schema::TYPE_INTEGER,
            'quantity' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->addForeignKey('fk-wishlist-user_id-user-id', '{{%wishlist}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-wishlist-product_id-product-id', '{{%wishlist}}', 'product_id', '{{%product}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-cart_item-user_id-user-id', '{{%cart_item}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-cart_item-product_id-product-id', '{{%cart_item}}', 'product_id', '{{%product}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180406_063011_wishlist cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180406_063011_wishlist cannot be reverted.\n";

        return false;
    }
    */
}
