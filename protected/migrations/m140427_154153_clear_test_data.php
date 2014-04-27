<?php

class m140427_154153_clear_test_data extends CDbMigration
{
    public function safeUp()
    {
        $this->truncateTable('{{authors}}');
        $this->truncateTable('{{collections}}');
        $this->truncateTable('{{images}}');
        $this->truncateTable('{{object_types}}');
        $this->truncateTable('{{objects}}');
        $this->truncateTable('{{photo_types}}');
        $this->truncateTable('{{temp_collection_object}}');
        $this->truncateTable('{{user_allowed_collection}}');
        $this->truncateTable('{{users}}');

        $this->insert('{{users}}', array(
            'surname' => 'Снигирев',
            'name' => 'Алексей',
            'middlename' => 'Евгеньевич',
            'initials' => 'Снигирев А.Е.',
            'position' => 'Разработчик',
            'date_create' => '2013-10-26 13:30:00',
            'date_modify' => '2013-10-26 13:30:00',
            'email' => 'snigirev.alexey@gmail.com',
            'password' => CPasswordHelper::hashPassword('e46gKql1MjIX'),
            'role' => 'administrator',
        ));

        $this->insert('{{users}}', array(
            'surname' => 'Белобородов',
            'name' => 'Артем',
            'middlename' => 'Владимирович',
            'initials' => 'Белобородов А.В.',
            'position' => 'Администратор',
            'date_create' => '2013-10-26 13:30:00',
            'date_modify' => '2013-10-26 13:30:00',
            'email' => 'bel.art@eposgroup.ru',
            'password' => CPasswordHelper::hashPassword('widbadmin'),
            'role' => 'administrator',
        ));
    }

    public function safeDown()
    {
        return false;
    }
}