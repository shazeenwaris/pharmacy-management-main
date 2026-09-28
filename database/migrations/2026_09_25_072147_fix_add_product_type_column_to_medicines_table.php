<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('medicines', 'product_type')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('product_type')->nullable()->after('name');
            });
        }

        DB::table('medicines')->whereNull('product_type')->update(['product_type' => 'medicine']);

        if (Schema::hasColumn('medicines', 'packaging_type')) {
            DB::table('medicines')->whereNull('packaging_type')->update(['packaging_type' => 'strip']);
        }

        if (Schema::hasColumn('medicines', 'has_expiry')) {
            DB::table('medicines')->whereNull('has_expiry')->update(['has_expiry' => 1]);
        }

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