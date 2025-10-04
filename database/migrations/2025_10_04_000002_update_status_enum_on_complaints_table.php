<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE complaints ALTER COLUMN status DROP DEFAULT;");
            DB::statement("ALTER TABLE complaints DROP CONSTRAINT IF EXISTS complaints_status_check;");

            DB::statement("UPDATE complaints SET status = 'process' WHERE status = 'in_progress';");
            DB::statement("UPDATE complaints SET status = 'completed' WHERE status = 'resolved';");

            DB::statement("ALTER TABLE complaints ADD CONSTRAINT complaints_status_check CHECK (status IN ('pending','process','completed','rejected'));");
            DB::statement("ALTER TABLE complaints ALTER COLUMN status SET DEFAULT 'pending';");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE complaints MODIFY status ENUM('pending','process','completed','rejected') NOT NULL DEFAULT 'pending';");
        } else {
            // Unsupported drivers: skip
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE complaints ALTER COLUMN status DROP DEFAULT;");
            DB::statement("ALTER TABLE complaints DROP CONSTRAINT IF EXISTS complaints_status_check;");
            DB::statement("ALTER TABLE complaints ADD CONSTRAINT complaints_status_check CHECK (status IN ('pending','in_progress','resolved','rejected'));");
            DB::statement("ALTER TABLE complaints ALTER COLUMN status SET DEFAULT 'pending';");

            DB::statement("UPDATE complaints SET status = 'in_progress' WHERE status = 'process';");
            DB::statement("UPDATE complaints SET status = 'resolved' WHERE status = 'completed';");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE complaints MODIFY status ENUM('pending','in_progress','resolved','rejected') NOT NULL DEFAULT 'pending';");
        }
    }
};
