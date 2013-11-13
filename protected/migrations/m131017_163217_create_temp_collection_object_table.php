<?php

class m131017_163217_create_temp_collection_object_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_temp_collection_object', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'collection_id' => 'int unsigned not null default 0',
            'object_id' => 'int unsigned not null default 0',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ));

        $this->createIndex('IX_tbl_temp_collection_object_collection_id', 'tbl_temp_collection_object', 'collection_id', false);
        $this->createIndex('IX_tbl_temp_collection_object_object_id', 'tbl_temp_collection_object', 'object_id', false);
        $this->createIndex('IX_tbl_temp_collection_object_sort', 'tbl_temp_collection_object', 'sort', false);
        $this->createIndex('IX_tbl_temp_collection_object_deleted', 'tbl_temp_collection_object', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_temp_collection_object');
    }
}