<?php

class m131107_181736_create_table_user_allowed_collection extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('tbl_user_allowed_collection', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'collection_id' => 'int unsigned not null default 0',
            'user_id' => 'int unsigned not null default 0',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
            'user_create' => 'int unsigned not null default 0',
            'user_modify' => 'int unsigned not null default 0',
            'user_delete' => 'int unsigned not null default 0'
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_tbl_user_allowed_collection_collection_id', 'tbl_user_allowed_collection', 'collection_id', false);
        $this->createIndex('IX_tbl_user_allowed_collection_user_id', 'tbl_user_allowed_collection', 'user_id', false);
        $this->createIndex('IX_tbl_user_allowed_collection_sort', 'tbl_user_allowed_collection', 'sort', false);
        $this->createIndex('IX_tbl_user_allowed_collection_deleted', 'tbl_user_allowed_collection', 'deleted', false);

    }

	public function safeDown()
	{
        $this->dropTable('tbl_user_allowed_collection');
	}

}