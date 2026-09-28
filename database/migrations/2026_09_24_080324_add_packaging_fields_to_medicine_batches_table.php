<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            if (! Schema::hasColumn('medicine_batches', 'boxes_received')) {
                $table->unsignedInteger('boxes_received')->default(0)->after('quantity');
            }

            if (! Schema::hasColumn('medicine_batches', 'strips_per_box')) {
                $table->unsignedInteger('strips_per_box')->default(1)->after('boxes_received');
            }

            if (! Schema::hasColumn('medicine_batches', 'units_per_strip')) {
                $table->unsignedInteger('units_per_strip')->default(1)->after('strips_per_box');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropColumn(['boxes_received', 'strips_per_box', 'units_per_strip']);
        });
    }
};