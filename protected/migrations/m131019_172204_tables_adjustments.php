<?php

class m131019_172204_tables_adjustments extends CDbMigration
{
	public function safeUp()
	{
        // collections
        // поле code - уникальное
        $this->createIndex('IX_collections_code', '{{collections}}', 'code', true);

        // images
        // поле original - уникальное
        $this->createIndex('IX_images_original', '{{images}}', 'original', true);
        // поле source - уникальное
        $this->createIndex('IX_images_source', '{{images}}', 'source', true);
	}

	public function safeDown()
	{
        // collections
        // поле code - не уникальное
        $this->dropIndex('IX_collections_code', '{{collections}}');
        // images
        // поле original - не уникальное
        $this->dropIndex('IX_images_original', '{{images}}');
        // поле source - не уникальное
        $this->dropIndex('IX_images_source', '{{images}}');
	}

}