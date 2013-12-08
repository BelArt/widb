<?php

class m131017_161658_create_images_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_images', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'object_id' => 'int unsigned not null default 0',
            'photo_type_id' => 'int unsigned not null default 0',
            'description' => 'text',
            'has_preview' => 'boolean not null default 0',
            'width' => 'decimal(8) unsigned not null default 0',
            'height' => 'decimal(8) unsigned not null default 0',
            'dpi' => 'decimal(8) unsigned not null default 0',
            'original' => 'varchar(150) not null default ""',
            'source' => 'varchar(150) not null default ""',
            'deepzoom' => 'boolean not null default 0',
            'request' => 'varchar(150) not null default ""',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_tbl_images_object_id', 'tbl_images', 'object_id', false);
        $this->createIndex('IX_tbl_images_photo_type_id', 'tbl_images', 'photo_type_id', false);
        $this->createIndex('IX_tbl_images_sort', 'tbl_images', 'sort', false);
        $this->createIndex('IX_tbl_images_deleted', 'tbl_images', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_images');
    }
}