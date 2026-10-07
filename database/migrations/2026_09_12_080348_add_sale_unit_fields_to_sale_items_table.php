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
             * Selected sellable unit from MedicineBatchPackaging.
             *
             * Example:
             * Tablet
             * Strip
             * Box
             *
             * FK will be added later, after the
             * medicine_batch_packagings table exists.
             */

            $table->unsignedBigInteger('medicine_batch_packaging_id')
                ->nullable()
                ->after('medicine_batch_id');


            /*
             * Snapshot of the unit name at the time of sale.
             *
             * Example:
             * Tablet / Strip / Box
             */

            $table->string('sale_unit', 100)
                ->nullable()
                ->after('medicine_batch_packaging_id');


            /*
             * Number of base units inside one sale unit.
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


            $table->dropColumn([
                'medicine_batch_packaging_id',
                'sale_unit',
                'conversion_factor',
            ]);

        });
    }
};