<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('generic_name')->nullable()->change();
            $table->string('strength')->nullable()->change();
            $table->unsignedBigInteger('dosage_form_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Not reversed automatically — these should stay nullable.
    }
};