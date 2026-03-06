<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddItemIconToMenus extends Migration
{
    public function up()
    {
        $this->forge->addColumn('menus', [
            'item_icon' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'icon'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('menus', 'item_icon');
    }
}
