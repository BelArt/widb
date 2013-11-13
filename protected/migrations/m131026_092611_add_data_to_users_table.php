<?php

class m131026_092611_add_data_to_users_table extends CDbMigration
{
	public function safeUp()
	{
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
        ));
	}

	public function safeDown()
	{
        $this->truncateTable('{{users}}');
	}

}