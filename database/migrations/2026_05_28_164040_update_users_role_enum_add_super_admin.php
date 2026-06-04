<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role 
            ENUM('super_admin','admin','admin_gudang','owner','customer') 
            NOT NULL DEFAULT 'customer'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role 
            ENUM('admin','owner','customer') 
            NOT NULL DEFAULT 'customer'");
    }
};