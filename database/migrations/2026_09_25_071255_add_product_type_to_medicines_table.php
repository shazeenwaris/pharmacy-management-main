<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add product_type only if it doesn't already exist
        if (! Schema::hasColumn('medicines', 'product_type')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('product_type')->nullable()->after('name');
            });
        }

        // Backfill: anything without a product_type is treated as a normal medicine
        DB::table('medicines')->whereNull('product_type')->update(['product_type' => 'medicine']);

        // Backfill packaging_type / has_expiry too, since they were created without defaults
        if (Schema::hasColumn('medicines', 'packaging_type')) {
            DB::table('medicines')->whereNull('packaging_type')->update(['packaging_type' => 'strip']);
        }

        if (Schema::hasColumn('medicines', 'has_expiry')) {
            DB::table('medicines')->whereNull('has_expiry')->update(['has_expiry' => 1]);
        }

        // Now that every row has a value, make product_type NOT NULL with a default
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('product_type')->default('medicine')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('medicines', 'product_type')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('product_type');
            });
        }
    }
};