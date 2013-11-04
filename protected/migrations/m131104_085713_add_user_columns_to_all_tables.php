<?php

class m131104_085713_add_user_columns_to_all_tables extends CDbMigration
{
	public function safeUp()
	{
        // collections
        $this->addColumn('{{collections}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{collections}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{collections}}', 'user_delete', 'int unsigned not null default 0');

        // objects
        $this->addColumn('{{objects}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{objects}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{objects}}', 'user_delete', 'int unsigned not null default 0');

        // images
        $this->addColumn('{{images}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{images}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{images}}', 'user_delete', 'int unsigned not null default 0');

        // temp_collection_object
        $this->addColumn('{{temp_collection_object}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{temp_collection_object}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{temp_collection_object}}', 'user_delete', 'int unsigned not null default 0');

        // authors
        $this->addColumn('{{authors}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{authors}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{authors}}', 'user_delete', 'int unsigned not null default 0');

        // object_types
        $this->addColumn('{{object_types}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{object_types}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{object_types}}', 'user_delete', 'int unsigned not null default 0');

        // photo_types
        $this->addColumn('{{photo_types}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{photo_types}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{photo_types}}', 'user_delete', 'int unsigned not null default 0');

        // users
        $this->addColumn('{{users}}', 'user_create', 'int unsigned not null default 0');
        $this->addColumn('{{users}}', 'user_modify', 'int unsigned not null default 0');
        $this->addColumn('{{users}}', 'user_delete', 'int unsigned not null default 0');

	}

	public function safeDown()
	{
        // collections
        $this->dropColumn('{{collections}}', 'user_create');
        $this->dropColumn('{{collections}}', 'user_modify');
        $this->dropColumn('{{collections}}', 'user_delete');

        // objects
        $this->dropColumn('{{objects}}', 'user_create');
        $this->dropColumn('{{objects}}', 'user_modify');
        $this->dropColumn('{{objects}}', 'user_delete');

        // images
        $this->dropColumn('{{images}}', 'user_create');
        $this->dropColumn('{{images}}', 'user_modify');
        $this->dropColumn('{{images}}', 'user_delete');

        // temp_collection_object
        $this->dropColumn('{{temp_collection_object}}', 'user_create');
        $this->dropColumn('{{temp_collection_object}}', 'user_modify');
        $this->dropColumn('{{temp_collection_object}}', 'user_delete');

        // authors
        $this->dropColumn('{{authors}}', 'user_create');
        $this->dropColumn('{{authors}}', 'user_modify');
        $this->dropColumn('{{authors}}', 'user_delete');

        // object_types
        $this->dropColumn('{{object_types}}', 'user_create');
        $this->dropColumn('{{object_types}}', 'user_modify');
        $this->dropColumn('{{object_types}}', 'user_delete');

        // photo_types
        $this->dropColumn('{{photo_types}}', 'user_create');
        $this->dropColumn('{{photo_types}}', 'user_modify');
        $this->dropColumn('{{photo_types}}', 'user_delete');

        // users
        $this->dropColumn('{{users}}', 'user_create');
        $this->dropColumn('{{users}}', 'user_modify');
        $this->dropColumn('{{users}}', 'user_delete');
	}

}