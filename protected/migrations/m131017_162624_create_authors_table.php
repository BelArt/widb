<?php

class m131017_162624_create_authors_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_authors', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'surname' => 'varchar(150) not null default ""',
            'name' => 'varchar(150) not null default ""',
            'middlename' => 'varchar(150) not null default ""',
            'initials' => 'varchar(150) not null default ""',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_authors_sort', 'tbl_authors', 'sort', false);
        $this->createIndex('IX_authors_deleted', 'tbl_authors', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_authors');
    }
}