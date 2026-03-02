<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRbacSystem extends Migration
{
    public function up()
    {
        // 1. Update Tabel roles: Tambah kolom scope
        $this->forge->addColumn('roles', [
            'scope' => [
                'type'       => 'ENUM',
                'constraint' => ['global', 'local'],
                'default'    => 'global',
                'after'      => 'role_name'
            ]
        ]);

        // 2. Buat Tabel permissions
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'permission_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permissions');

        // 3. Buat Tabel role_permissions (Pivot)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'permission_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('role_permissions');

        // 4. Update Tabel user_desa: Tambah kolom category
        $this->forge->addColumn('user_desa', [
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['rtlh', 'kumuh'],
                'default'    => 'rtlh',
                'after'      => 'desa_id'
            ]
        ]);
    }

    public function down()
    {
        // Hapus Tabel
        $this->forge->dropTable('role_permissions');
        $this->forge->dropTable('permissions');

        // Hapus Kolom
        $this->forge->dropColumn('roles', 'scope');
        $this->forge->dropColumn('user_desa', 'category');
    }
}
