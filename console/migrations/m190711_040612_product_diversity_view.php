<?php

use yii\db\Migration;

/**
 * Class m190711_040612_product_diversity_view
 */
class m190711_040612_product_diversity_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE or REPLACE VIEW product_diversity_view AS
            SELECT p.id, pd.id as diversity_id, category_id, p.is_in_stock, is_novelty,
            diversity, size, price,
            IF(diversity,CONCAT(convert(p.title using utf8), convert(" \'" using utf8), convert(pd.title using utf8), convert("\'" using utf8)),convert(p.title using utf8)) as title,
            IF(diversity,convert(pd.article using utf8),convert(p.article using utf8)) as article,
            IF(diversity,pd.is_active,p.is_active) as is_active,
            IF(diversity,pd.count,p.count) as count
            FROM product as p
            LEFT JOIN product_diversity as pd ON (p.id=pd.product_id)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190711_040612_product_diversity_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190711_040612_product_diversity_view cannot be reverted.\n";

        return false;
    }
    */
}
