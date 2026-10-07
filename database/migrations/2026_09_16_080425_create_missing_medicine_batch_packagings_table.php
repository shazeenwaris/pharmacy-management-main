<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('medicine_batch_packagings')) {
            return;
        }

        Schema::create('medicine_batch_packagings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medicine_batch_id')
                ->constrained('medicine_batches')
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            $table->decimal('conversion_factor', 10, 4)
                ->default(1);

            $table->decimal('sale_price', 10, 2)
                ->nullable();

            $table->boolean('can_sell')
                ->default(true);

            $table->integer('sort_order')
                ->default(0);

            $table->timestamps();

            $table->unique(
                ['medicine_batch_id', 'unit_id'],
                'batch_unit_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_batch_packagings');
    }
};