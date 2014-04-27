<?php

class m131017_162308_create_users_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_users', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'surname' => 'varchar(150) not null default ""',
            'name' => 'varchar(150) not null default ""',
            'middlename' => 'varchar(150) not null default ""',
            'initials' => 'varchar(150) not null default ""',
            'position' => 'varchar(150) not null default ""',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_users_sort', 'tbl_users', 'sort', false);
        $this->createIndex('IX_users_deleted', 'tbl_users', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_users');
    }
}