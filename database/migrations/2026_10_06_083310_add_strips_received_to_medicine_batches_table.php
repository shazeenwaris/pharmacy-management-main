<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            // Loose strips received that don't make up a full box
            // (e.g. supplier sent 5 strips instead of a whole box).
            $table->unsignedInteger('strips_received')->default(0)->after('boxes_received');
        });
    }

    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropColumn('strips_received');
        });
    }
};