<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< HEAD
        // Superseded by 2026_09_16_080425_create_missing_medicine_batch_packagings_table.php
        // This migration referenced a non-existent 'medicine_packagings' table and is intentionally left empty.
=======
        Schema::create('medicine_batch_packagings', function (Blueprint $table) {
            $table->id();

            /*
             * The batch this packaging belongs to.
             */
            $table->foreignId('medicine_batch_id')
                ->constrained('medicine_batches')
                ->cascadeOnDelete();

            /*
             * The unit used for selling this batch.
             *
             * Examples:
             * Tablet
             * Strip
             * Box
             * Bottle
             */
            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            /*
             * Number of base units represented by one
             * selling unit.
             *
             * Tablet = 1
             * Strip  = 10
             * Box    = 100
             */
            $table->decimal('conversion_factor', 10, 4)
                ->default(1);

            /*
             * Optional selling price for this packaging.
             */
            $table->decimal('sale_price', 10, 2)
                ->nullable();

            /*
             * Whether this packaging can be selected in POS.
             */
            $table->boolean('can_sell')
                ->default(true);

            $table->integer('sort_order')
                ->default(0);

            $table->timestamps();

            /*
             * One unit can only be configured once
             * for the same batch.
             */
            $table->unique(
                ['medicine_batch_id', 'unit_id'],
                'batch_unit_unique'
            );
        });
>>>>>>> 404f8ffa9cd0037be434329645d15733c8b2fee9
    }

    public function down(): void
    {
        //
    }
};