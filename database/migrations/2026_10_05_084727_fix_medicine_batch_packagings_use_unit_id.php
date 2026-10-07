<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_batch_packagings', function (Blueprint $table) {
            if (Schema::hasColumn('medicine_batch_packagings', 'medicine_packaging_id')) {
                $table->dropColumn('medicine_packaging_id');
            }

            if (! Schema::hasColumn('medicine_batch_packagings', 'unit_id')) {
                $table->foreignId('unit_id')
                    ->after('medicine_batch_id')
                    ->constrained('units')
                    ->cascadeOnDelete();
            }
        });

        Schema::table('medicine_batch_packagings', function (Blueprint $table) {
            $table->unique(['medicine_batch_id', 'unit_id'], 'batch_unit_unique');
        });
    }

    public function down(): void
    {
        //
    }
};