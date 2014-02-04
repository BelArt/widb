<?php

class m131019_160657_fill_db_with_test_data extends CDbMigration
{
    public function safeUp()
    {
        // photo_types
        $this->insert('{{photo_types}}', array(
            'name' => 'Однокадровая',
            'description' => 'Однокадровая съемка',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{photo_types}}', array(
            'name' => 'Многокадровая',
            'description' => 'Многокадровая съемка',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        // object_types
        $this->insert('{{object_types}}', array(
            'name' => 'Живопись',
            'description' => 'Картины и т.д.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{object_types}}', array(
            'name' => 'Скульптура',
            'description' => 'Статуи, бюсты, и т.д.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        // authors
        $this->insert('{{authors}}', array(
            'surname' => 'Иванов',
            'name' => 'Петр',
            'middlename' => 'Николаевич',
            'initials' => 'Иванов П.Н.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{authors}}', array(
            'surname' => 'Петров',
            'name' => 'Петр',
            'middlename' => 'Николаевич',
            'initials' => 'Петров П.Н.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        // collections
        $this->insert('{{collections}}', array(
            'parent_id' => '0',
            'name' => 'Искусство 19 века',
            'description' => 'Искусство 19 века от разных авторов',
            'code' => 'art_19_century',
            'temporary' => '0',
            'has_preview' => '0',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{collections}}', array(
            'parent_id' => '1',
            'name' => 'Живопись 19 века',
            'description' => 'Живопись 19 века от разных авторов',
            'code' => 'painting_19_century',
            'temporary' => '0',
            'has_preview' => '0',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{collections}}', array(
            'parent_id' => '1',
            'name' => 'Скульптура 19 века',
            'description' => 'Скульптура 19 века от разных авторов',
            'code' => 'sculpture_19_century',
            'temporary' => '0',
            'has_preview' => '0',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{collections}}', array(
            'parent_id' => '0',
            'name' => 'Временная коллекция',
            'description' => 'Временная коллекция',
            'code' => 'temp_collection_1',
            'temporary' => '1',
            'has_preview' => '0',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        // objects
        $this->insert('{{objects}}', array(
            'author_id' => '1',
            'type_id' => '1',
            'collection_id' => '2',
            'name' => 'Пейзаж на закате',
            'description' => 'Красивый пейзаж',
            'inventory_number' => 'П-135',
            'inventory_number_en' => '',
            'code' => 'p_135',
            'width' => '135.5',
            'height' => '140.7',
            'depth' => '5',
            'has_preview' => '0',
            'department' => 'Отдел коллекций',
            'keeper' => 'Иванов И.И.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{objects}}', array(
            'author_id' => '2',
            'type_id' => '1',
            'collection_id' => '2',
            'name' => 'Пейзаж на рассвете',
            'description' => 'Красивый пейзаж',
            'inventory_number' => 'П-166',
            'inventory_number_en' => '',
            'code' => 'p_166',
            'width' => '235.5',
            'height' => '240.7',
            'depth' => '4.88',
            'has_preview' => '0',
            'department' => 'Отдел коллекций',
            'keeper' => 'Иванов И.И.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{objects}}', array(
            'author_id' => '1',
            'type_id' => '2',
            'collection_id' => '3',
            'name' => 'Скульптура большая',
            'description' => 'Скульптура большая',
            'inventory_number' => 'С-166',
            'inventory_number_en' => '',
            'code' => 'c_166',
            'width' => '235.5',
            'height' => '240.7',
            'depth' => '43.88',
            'has_preview' => '0',
            'department' => 'Отдел коллекций',
            'keeper' => 'Иванов И.И.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{objects}}', array(
            'author_id' => '2',
            'type_id' => '2',
            'collection_id' => '3',
            'name' => 'Скульптура маленькая',
            'description' => 'Скульптура маленькая',
            'inventory_number' => 'С-444',
            'inventory_number_en' => '',
            'code' => 'c_444',
            'width' => '235.5',
            'height' => '240.7',
            'depth' => '43.88',
            'has_preview' => '0',
            'department' => 'Отдел коллекций',
            'keeper' => 'Иванов И.И.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{objects}}', array(
            'author_id' => '2',
            'type_id' => '2',
            'collection_id' => '2',
            'name' => 'Скульптура для временной коллекции',
            'description' => 'Скульптура временной коллекции',
            'inventory_number' => 'С-888',
            'inventory_number_en' => '',
            'code' => 'c_888',
            'width' => '235.5',
            'height' => '240.7',
            'depth' => '43.88',
            'has_preview' => '0',
            'department' => 'Отдел коллекций',
            'keeper' => 'Иванов И.И.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{objects}}', array(
            'author_id' => '1',
            'type_id' => '1',
            'collection_id' => '3',
            'name' => 'Скульптура для временной коллекции вторая',
            'description' => 'Скульптура временной коллекции вторая',
            'inventory_number' => 'С-999',
            'inventory_number_en' => '',
            'code' => 'c_999',
            'width' => '235.5',
            'height' => '240.7',
            'depth' => '43.88',
            'has_preview' => '0',
            'department' => 'Отдел коллекций',
            'keeper' => 'Иванов И.И.',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        // temp_collection_object
        $this->insert('{{temp_collection_object}}', array(
            'collection_id' => '4',
            'object_id' => '5',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{temp_collection_object}}', array(
            'collection_id' => '4',
            'object_id' => '6',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        // images
        $this->insert('{{images}}', array(
            'object_id' => '1',
            'photo_type_id' => '1',
            'description' => 'Съемка проходила в Москве',
            'has_preview' => '0',
            'width' => '5000',
            'height' => '3000',
            'dpi' => '355',
            'original' => '/some/path/to/original',
            'source' => '/some/path/to/source',
            'deepzoom' => '0',
            'request' => 'Заявка №35',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{images}}', array(
            'object_id' => '2',
            'photo_type_id' => '1',
            'description' => 'Съемка проходила в Москве',
            'has_preview' => '0',
            'width' => '5000',
            'height' => '3000',
            'dpi' => '355',
            'original' => '/some/path/to/original2',
            'source' => '/some/path/to/source2',
            'deepzoom' => '0',
            'request' => 'Заявка №353',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
        $this->insert('{{images}}', array(
            'object_id' => '2',
            'photo_type_id' => '2',
            'description' => 'Съемка проходила в Москве',
            'has_preview' => '0',
            'width' => '5000',
            'height' => '3000',
            'dpi' => '355',
            'original' => '/some/path/to/original3',
            'source' => '/some/path/to/source3',
            'deepzoom' => '0',
            'request' => 'Заявка №353',
            'date_create' => '2013-10-19 21:03:00',
            'date_modify' => '2013-10-19 21:03:00',
        ));
    }

	public function safeDown()
	{
        // photo_types
        $this->truncateTable('{{photo_types}}');
        // object_types
        $this->truncateTable('{{object_types}}');
        // authors
        $this->truncateTable('{{authors}}');
        // collections
        $this->truncateTable('{{collections}}');
        // objects
        $this->truncateTable('{{objects}}');
        // temp_collection_object
        $this->truncateTable('{{temp_collection_object}}');
        // images
        $this->truncateTable('{{images}}');

	}

}