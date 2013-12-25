<?php

class m131202_083933_add_period_column_to_objects_table extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{objects}}', 'period', 'varchar(150) not null default ""');
    }

    public function safeDown()
    {
        $this->dropColumn('{{objects}}', 'period');
    }

}