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
            // Drop default and existing check
            DB::statement("ALTER TABLE complaints ALTER COLUMN priority DROP DEFAULT;");
            DB::statement("ALTER TABLE complaints DROP CONSTRAINT IF EXISTS complaints_priority_check;");

            // Normalize old values
            DB::statement("UPDATE complaints SET priority = 'medium' WHERE priority = 'normal';");
            DB::statement("UPDATE complaints SET priority = 'high' WHERE priority = 'urgent';");

            // Add new check and default
            DB::statement("ALTER TABLE complaints ADD CONSTRAINT complaints_priority_check CHECK (priority IN ('low','medium','high'));");
            DB::statement("ALTER TABLE complaints ALTER COLUMN priority SET DEFAULT 'low';");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE complaints MODIFY priority ENUM('low','medium','high') NOT NULL DEFAULT 'low';");
        } else {
            // Skip for unsupported drivers (e.g., sqlite) to avoid table rebuild here
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE complaints ALTER COLUMN priority DROP DEFAULT;");
            DB::statement("ALTER TABLE complaints DROP CONSTRAINT IF EXISTS complaints_priority_check;");
            DB::statement("ALTER TABLE complaints ADD CONSTRAINT complaints_priority_check CHECK (priority IN ('low','normal','high','urgent'));");
            DB::statement("ALTER TABLE complaints ALTER COLUMN priority SET DEFAULT 'normal';");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE complaints MODIFY priority ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal';");
        }
    }
};
