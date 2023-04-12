<?php

use yii\db\Migration;

/**
 * Class m210220_172801_name
 */
class m210220_172801_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){

        $this->createTable('books',[
            'id'=> $this->primaryKey()->unique(),
            'name_book'=> $this->string(255)->notNull(),
            'short_description' => $this->string(512),
            'image_path'=> $this->string(512),
            'publication_date'=> $this->integer(),

        ]);

        $this->createTable('authors',[
            'id'=> $this->primaryKey()->unique(),
            'surname'=> $this->string(50)->notNull(),
            'name' => $this->string(50)->notNull(),
            'middle_name'=> $this->string(50),
//            'created' => $this->dateTime()->notNull(),
//            'updated' => $this->timestamp()->notNull(),
            
        ]);

        $this->createTable('pivot_table',[
            'id_book'=> $this->integer()->notNull(),
            'id_author'=>$this->integer()->notNull(),
        ]);
        // Создаем внешний ключ 'id_author' для таблицы 'pivot_table'
        $this->addForeignKey(
            'fk-pivot_table-id_author',
            '{{%pivot_table}}',
            'id_author',
            '{{%authors}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Создаем внешний ключ 'id_book' для таблицы 'pivot_table'
        $this->addForeignKey(
            'fk-pivot_table-id_book',
            '{{%pivot_table}}',
            'id_book',
            '{{%books}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('books');
        $this->dropTable('authors');
        $this->dropTable('pivot_table');

        echo "m210220_172801_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210220_172801_name cannot be reverted.\n";

        return false;
    }
    */
}
