<?php

class m131017_152253_create_collections_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_collections', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'parent_id' => 'int unsigned not null default 0',
            'name' => 'varchar(150) not null default ""',
            'description' => 'text',
            'code' => 'varchar(150) not null default ""',
            'image' => 'varchar(150) not null default ""',
            'temporary' => 'boolean not null default 0',
            'has_preview' => 'boolean not null default 0',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_collections_parent_id', 'tbl_collections', 'parent_id', false);
        $this->createIndex('IX_collections_sort', 'tbl_collections', 'sort', false);
        $this->createIndex('IX_collections_deleted', 'tbl_collections', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_collections');
    }
}