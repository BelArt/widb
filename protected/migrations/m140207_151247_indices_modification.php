<?php

class m140207_151247_indices_modification extends CDbMigration
{
	public function safeUp()
	{
        $this->dropIndex('IX_collections_code', '{{collections}}');
        $this->dropIndex('IX_images_source', '{{images}}');
        $this->dropIndex('IX_images_original', '{{images}}');

	}

	public function safeDown()
	{
        $this->createIndex('IX_collections_code', '{{collections}}', 'code');
        $this->createIndex('IX_images_source', '{{images}}', 'source');
        $this->createIndex('IX_images_original', '{{images}}', 'source');
	}

}