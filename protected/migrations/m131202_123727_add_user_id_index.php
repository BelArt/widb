<?php

class m131202_123727_add_user_id_index extends CDbMigration
{
    public function safeUp()
    {
        $this->createIndex('IX_authors_user_create', '{{authors}}', 'user_create');
        $this->createIndex('IX_collections_user_create', '{{collections}}', 'user_create');
        $this->createIndex('IX_images_user_create', '{{images}}', 'user_create');
        $this->createIndex('IX_objects_user_create', '{{objects}}', 'user_create');
        $this->createIndex('IX_object_types_user_create', '{{object_types}}', 'user_create');
        $this->createIndex('IX_photo_types_user_create', '{{photo_types}}', 'user_create');
        $this->createIndex('IX_temp_collection_object_user_create', '{{temp_collection_object}}', 'user_create');
        $this->createIndex('IX_user_allowed_collection_user_create', '{{user_allowed_collection}}', 'user_create');
        $this->createIndex('IX_users_user_create', '{{users}}', 'user_create');

    }

    public function safeDown()
    {
        $this->dropIndex('IX_authors_user_create', '{{authors}}');
        $this->dropIndex('IX_collections_user_create', '{{collections}}');
        $this->dropIndex('IX_images_user_create', '{{images}}');
        $this->dropIndex('IX_objects_user_create', '{{objects}}');
        $this->dropIndex('IX_object_types_user_create', '{{object_types}}');
        $this->dropIndex('IX_photo_types_user_create', '{{photo_types}}');
        $this->dropIndex('IX_temp_collection_object_user_create', '{{temp_collection_object}}');
        $this->dropIndex('IX_user_allowed_collection_user_create', '{{user_allowed_collection}}');
        $this->dropIndex('IX_users_user_create', '{{users}}');
    }
}