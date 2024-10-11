<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration {
    public function up(): void
    {
        $this->createOrUpdateProcedure('get_payments_over_time', 'sql/get_payments_over_time.sql');
        $this->createOrUpdateProcedure('get_top_microsites_by_transactions', 'sql/get_top_microsites_by_transactions.sql');
        $this->createOrUpdateProcedure('get_invoice_distribution', 'sql/get_invoice_distribution.sql');
        $this->createOrUpdateProcedure('get_subscription_distribution', 'sql/get_subscription_distribution.sql');
        $this->createOrUpdateProcedure('get_approved_transactions_by_microsite_type', 'sql/get_approved_transactions_by_microsite_type.sql');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_payments_over_time');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_top_microsites_by_transactions');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_invoice_distribution');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_subscription_distribution');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_approved_transactions_by_microsite_type');
    }

    private function createOrUpdateProcedure(string $procedureName, string $filePath): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS $procedureName");

        DB::unprepared(File::get(database_path($filePath)));
    }
};
