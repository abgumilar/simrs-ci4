<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRBACAndMenus extends Migration
{
    public function up()
    {
        // 1. Roles Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roles');

        // 2. Permissions Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 255], // e.g., clinical.rajal.view
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('permissions');

        // 3. Role Permissions (Many-to-Many)
        $this->forge->addField([
            'role_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'permission_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('role_permissions');

        // 4. User Roles (Many-to-Many)
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'role_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_roles');

        // 5. Menus Table (Dynamic Sidebar)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'parent_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'environment' => ['type' => 'VARCHAR', 'constraint' => 50], // Pelayanan, Penunjang, etc.
            'title'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'url'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'permission'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sequence'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active'   => ['type' => 'BOOLEAN', 'default' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('menus');
    }

    public function down()
    {
        $this->forge->dropTable('menus');
        $this->forge->dropTable('user_roles');
        $this->forge->dropTable('role_permissions');
        $this->forge->dropTable('permissions');
        $this->forge->dropTable('roles');
    }
}
