<?php

class m131216_154919_add_code_column_to_images_table extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{images}}', 'code', 'varchar(150) not null default ""');
        $this->dropColumn('{{objects}}', 'inventory_number_en');
    }

    public function safeDown()
    {
        $this->dropColumn('{{images}}', 'code');
        $this->addColumn('{{objects}}', 'inventory_number_en', 'varchar(50) not null default ""');
    }
}