<?php namespace Adnduweb\Ci4Media\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_medias extends Migration
{
	public function up()
	{
		// medias
		$fields = [
			'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'uuid'       => ['type' => 'BINARY', 'constraint' => 16, 'unique' => true],
			'filename'   => ['type' => 'VARCHAR', 'constraint' => 255],
			'localname'  => ['type' => 'VARCHAR', 'constraint' => 255],
			'clientname' => ['type' => 'VARCHAR', 'constraint' => 255],
			'type'       => ['type' => 'VARCHAR', 'constraint' => 255],
			'size'       => ['type' => 'INT', 'unsigned' => true],
			'ext'        => ['type' => 'VARCHAR', 'constraint' => 48],
			'thumbnail'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'created_at' => ['type' => 'DATETIME', 'null' => true],
			'updated_at' => ['type' => 'DATETIME', 'null' => true],
			'deleted_at' => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('filename');
		$this->forge->addKey('created_at');

		$this->forge->createTable('medias');

		// medias Langs
		$fields = [
            'id_media_lang' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'media_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'       => ['type' => 'INT', 'constraint' => 11],
            'titre'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'legende'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'   => ['type' => 'VARCHAR', 'constraint' => 255]
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_media_lang', true);
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('media_id', 'medias', 'id', false, 'CASCADE');
        $this->forge->createTable('medias_langs', true);

		// medias_users
		$fields = [
			'media_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
			'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
			'created_at' => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addUniqueKey(['media_id', 'user_id']);
		$this->forge->addUniqueKey(['user_id', 'media_id']);
		// $this->forge->addForeignKey('media_id', 'medias', 'id', false, 'CASCADE');
		// $this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');

		$this->forge->createTable('medias_users');

		// downloads
		$fields = [
			'media_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
			'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
			'created_at' => ['type' => 'DATETIME', 'null' => true],
		];

		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey(['media_id', 'user_id']);
		$this->forge->addKey(['user_id', 'media_id']);

		$this->forge->createTable('medias_downloads');
	}

	public function down()
	{
		$this->forge->dropTable('medias');
		$this->forge->dropTable('medias_langs');
		$this->forge->dropTable('medias_users');
		$this->forge->dropTable('medias_downloads');
	}
}
