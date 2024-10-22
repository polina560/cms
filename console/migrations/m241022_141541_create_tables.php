<?php

use yii\db\Migration;

/**
 * Class m241022_141541_create_tables
 */
class m241022_141541_create_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('PostCategory', [
            'name' => $this->string()->unique(),

        ]);
        $this->createTable('Post', [
            'user_id' => $this->integer(),
            'title' => $this->string(),
            'text' => $this->text(),
            'post_category_id' => $this->integer(),
            'status' => $this->integer(),
            'image' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()

        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('PostCategory');
        $this->dropTable('Post');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241022_141541_create_tables cannot be reverted.\n";

        return false;
    }
    */
}
