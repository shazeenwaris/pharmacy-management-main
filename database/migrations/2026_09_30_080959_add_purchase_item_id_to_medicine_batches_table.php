<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            if (! Schema::hasColumn('medicine_batches', 'purchase_item_id')) {
                $table->foreignId('purchase_item_id')
                    ->nullable()
                    ->after('medicine_id')
                    ->constrained('purchase_items')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropForeign(['purchase_item_id']);
            $table->dropColumn('purchase_item_id');
        });
    }
};