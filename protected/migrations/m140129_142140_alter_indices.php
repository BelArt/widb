<?php

class m140129_142140_alter_indices extends CDbMigration
{
	public function safeUp()
	{
        $this->dropIndex('IX_collections_code', '{{collections}}');
        $this->createIndex('IX_collections_code', '{{collections}}', 'code');

        $this->dropIndex('IX_images_original', '{{images}}');
        $this->createIndex('IX_images_original', '{{images}}', 'original');

        $this->dropIndex('IX_images_original', '{{images}}');
        $this->createIndex('IX_images_original', '{{images}}', 'source');
	}

	public function safeDown()
	{
        $this->dropIndex('IX_collections_code', '{{collections}}');
        $this->createIndex('IX_collections_code', '{{collections}}', 'code', true);

        $this->dropIndex('IX_images_original', '{{images}}');
        $this->createIndex('IX_images_original', '{{images}}', 'original', true);

        $this->dropIndex('IX_images_original', '{{images}}');
        $this->createIndex('IX_images_original', '{{images}}', 'source', true);
	}

}