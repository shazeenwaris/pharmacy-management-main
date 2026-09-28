<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('medicines', 'packaging_type')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('packaging_type')->nullable()->after('product_type');
            });
        }

        if (! Schema::hasColumn('medicines', 'has_expiry')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->boolean('has_expiry')->nullable()->after('packaging_type');
            });
        }

        DB::table('medicines')->whereNull('packaging_type')->update(['packaging_type' => 'strip']);
        DB::table('medicines')->whereNull('has_expiry')->update(['has_expiry' => 1]);

        Schema::table('medicines', function (Blueprint $table) {
            $table->string('packaging_type')->default('strip')->nullable(false)->change();
            $table->boolean('has_expiry')->default(1)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (Schema::hasColumn('medicines', 'packaging_type')) {
                $table->dropColumn('packaging_type');
            }

            if (Schema::hasColumn('medicines', 'has_expiry')) {
                $table->dropColumn('has_expiry');
            }
        });
    }
};