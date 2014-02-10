<?php

class m140210_083709_alter_codes extends CDbMigration
{
    public function safeUp()
    {
        for ($i = 1; $i <= 6; $i++) {
            $this->update('{{objects}}', array('code' => 'p_'.$i), 'id = '.$i);
        }

        for ($i = 1; $i <= 3; $i++) {
            $this->update('{{images}}', array('code' => 'i_'.$i), 'id = '.$i);
        }
    }

    public function safeDown()
    {
        $this->update('{{objects}}', array('code' => 'p_123'));
        $this->update('{{images}}', array('code' => ''));
    }
}