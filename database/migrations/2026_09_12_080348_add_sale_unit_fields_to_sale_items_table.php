<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            /*
             * Selected packaging/sale unit.
             *
             * Example:
             * Tablet
             * Strip
             * Box
             */
            if (! Schema::hasColumn('sale_items', 'medicine_packaging_id')) {
                $table->foreignId('medicine_packaging_id')
                    ->nullable()
                    ->after('medicine_batch_id')
                    ->constrained('medicine_packagings')
                    ->nullOnDelete();
            }

            /*
             * Snapshot of the unit name at the time of sale.
             *
             * Example:
             * Tablet / Strip / Box
             */
            if (! Schema::hasColumn('sale_items', 'sale_unit')) {
                $table->string('sale_unit', 100)
                    ->nullable()
                    ->after('medicine_packaging_id');
            }

            /*
             * How many base units are inside one sale unit.
             *
             * Tablet = 1
             * Strip  = 10
             * Box    = 100
             */
            if (! Schema::hasColumn('sale_items', 'conversion_factor')) {
                $table->decimal('conversion_factor', 12, 3)
                    ->default(1)
                    ->after('sale_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (Schema::hasColumn('sale_items', 'medicine_packaging_id')) {
                $table->dropForeign(['medicine_packaging_id']);
            }

            $table->dropColumn(array_filter([
                Schema::hasColumn('sale_items', 'medicine_packaging_id') ? 'medicine_packaging_id' : null,
                Schema::hasColumn('sale_items', 'sale_unit') ? 'sale_unit' : null,
                Schema::hasColumn('sale_items', 'conversion_factor') ? 'conversion_factor' : null,
            ]));
        });
    }
};